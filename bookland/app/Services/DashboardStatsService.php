<?php

namespace App\Services;

use App\Models\Action;
use App\Models\ActionAmelioration;
use App\Models\Adoption;
use App\Models\Bss;
use App\Models\Compte;
use App\Models\DemandeSpecimen;
use App\Models\Event;
use App\Models\Examen;
use App\Models\Formation;
use App\Models\NonConformite;
use App\Models\Reclamation;
use App\Models\Tache;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardStatsService
{
    private const ACTION_REALISED_STATUTS = ['valide', 'realise'];
    private const EXAMEN_COMPLETED_STATUTS = ['communication_resultats', 'livraison_attestations'];
    private const BSS_DELIVERED_STATUTS = ['livre', 'adopte', 'retour'];

    public function resolveDelegateId(User $user, ?int $requestedId): ?int
    {
        if (!$requestedId) {
            return $user->role === 'delegue' ? $user->id : null;
        }

        if (in_array($user->role, ['admin', 'abo'])) {
            return User::where('id', $requestedId)->where('role', 'delegue')->exists()
                ? $requestedId
                : null;
        }

        if ($user->role === 'rbo') {
            $allowed = $this->delegateIdsForRbo($user);
            return $allowed->contains($requestedId) ? $requestedId : null;
        }

        return null;
    }

    public function delegatesForRbo(int $rboId): Collection
    {
        return User::whereHas('zones', function ($q) use ($rboId) {
            $q->where('rbo_id', $rboId);
        })->where('role', 'delegue')->orderBy('nom')->get();
    }

    public function delegateIdsForRbo(User $rbo): Collection
    {
        return $rbo->zonesAsRbo->flatMap->delegates->pluck('id')->unique()->values();
    }

    public function delegateIdsForRboId(int $rboId): Collection
    {
        return User::whereHas('zones', fn ($q) => $q->where('rbo_id', $rboId))
            ->where('role', 'delegue')
            ->pluck('id');
    }

    public function build(User $user, ?int $delegateId = null, ?int $rboId = null): array
    {
        $effectiveDelegateId = $this->resolveDelegateId($user, $delegateId)
            ?? ($user->role === 'delegue' ? $user->id : null);

        $payload = [
            'role' => $user->role,
            'delegate_id' => $effectiveDelegateId,
            'rbo_id' => $rboId,
            'delegate' => null,
            'delegate_stats' => null,
            'rbo_stats' => null,
            'admin_stats' => null,
        ];

        if ($effectiveDelegateId) {
            $delegate = User::find($effectiveDelegateId);
            $payload['delegate'] = $delegate ? [
                'id' => $delegate->id,
                'name' => trim($delegate->prenom . ' ' . $delegate->nom),
            ] : null;
            $payload['delegate_stats'] = $this->delegateStats($effectiveDelegateId);
        }

        if ($user->role === 'rbo') {
            $payload['rbo_stats'] = $this->rboStats($user);
        } elseif (in_array($user->role, ['admin', 'abo'])) {
            $payload['admin_stats'] = $this->adminStats();
            if ($rboId) {
                $payload['rbo_stats'] = $this->rboStatsForRboId($rboId);
            }
        }

        return $payload;
    }

    public function delegateStats(int $delegateId): array
    {
        return [
            'schools' => $this->schoolStats($delegateId),
            'actions' => $this->actionStats($delegateId),
            'specimens' => $this->specimenStats($delegateId),
            'activities' => $this->activityStats($delegateId),
        ];
    }

    public function rboStats(User $rbo): array
    {
        $delegateIds = $this->delegateIdsForRbo($rbo);

        return [
            'team' => [
                'managed_delegates' => $delegateIds->count(),
                'managed_schools' => Compte::whereIn('delegue_id', $delegateIds)->count(),
                'active_delegates' => User::whereIn('id', $delegateIds)->where('is_active', true)->count(),
            ],
            'validation' => $this->validationStatsForDelegates($delegateIds),
        ];
    }

    public function rboStatsForRboId(int $rboId): array
    {
        $delegateIds = $this->delegateIdsForRboId($rboId);

        return [
            'team' => [
                'managed_delegates' => $delegateIds->count(),
                'managed_schools' => Compte::whereIn('delegue_id', $delegateIds)->count(),
                'active_delegates' => User::whereIn('id', $delegateIds)->where('is_active', true)->count(),
            ],
            'validation' => $this->validationStatsForDelegates($delegateIds),
        ];
    }

    public function adminStats(): array
    {
        $delivered = Bss::whereIn('statut', self::BSS_DELIVERED_STATUTS)->count();
        $adopted = Bss::where('statut', 'adopte')->count();
        $retour = Bss::where('statut', 'retour')->count();

        return [
            'executive' => [
                'total_users' => User::count(),
                'total_delegates' => User::where('role', 'delegue')->count(),
                'total_rbo' => User::where('role', 'rbo')->count(),
                'total_schools' => Compte::count(),
            ],
            'commercial' => [
                'total_actions' => Action::count(),
                'total_trainings' => Formation::count(),
                'total_exams' => Examen::count(),
                'total_specimens' => Bss::count(),
                'total_adoptions' => Adoption::count(),
            ],
            'quality' => [
                'total_complaints' => Reclamation::count(),
                'total_non_conformities' => NonConformite::count(),
                'total_improvement_actions' => ActionAmelioration::count(),
            ],
            'crm_distribution' => [
                'users_by_role' => User::select('role', DB::raw('count(*) as total'))
                    ->groupBy('role')
                    ->pluck('total', 'role')
                    ->toArray(),
                'specimens_by_status' => Bss::select('statut', DB::raw('count(*) as total'))
                    ->groupBy('statut')
                    ->pluck('total', 'statut')
                    ->toArray(),
            ],
            'rates' => [
                'adoption_rate' => $this->rate($adopted, $delivered),
                'retour_rate' => $this->rate($retour, $delivered),
            ],
        ];
    }

    private function schoolStats(int $delegateId): array
    {
        $now = Carbon::now();

        $visitsThisMonth = Action::query()
            ->where('delegue_id', $delegateId)
            ->whereIn('statut', self::ACTION_REALISED_STATUTS)
            ->whereNotNull('date_realisation')
            ->whereYear('date_realisation', $now->year)
            ->whereMonth('date_realisation', $now->month)
            ->whereHas('lignes', fn ($q) => $q->where('moyen', 'Visite'))
            ->distinct()
            ->count('compte_id');

        return [
            'assigned_total' => Compte::where('delegue_id', $delegateId)->count(),
            'visited_this_month' => $visitsThisMonth,
        ];
    }

    private function actionStats(int $delegateId): array
    {
        $base = Action::where('delegue_id', $delegateId);

        $created = (clone $base)->count();
        $realised = (clone $base)->whereIn('statut', self::ACTION_REALISED_STATUTS)->count();
        $pending = (clone $base)->whereNotIn('statut', array_merge(self::ACTION_REALISED_STATUTS, ['annule']))->count();

        $countsByMonth = Action::query()
            ->where('delegue_id', $delegateId)
            ->whereIn('statut', self::ACTION_REALISED_STATUTS)
            ->whereNotNull('date_realisation')
            ->where('date_realisation', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->get(['date_realisation'])
            ->groupBy(fn (Action $action) => $action->date_realisation->format('Y-m'))
            ->map->count();

        $labels = [];
        $values = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = Carbon::now()->subMonths($i);
            $labels[] = $d->translatedFormat('M Y');
            $values[] = (int) ($countsByMonth[$d->format('Y-m')] ?? 0);
        }

        $byStatus = (clone $base)
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        return [
            'created' => $created,
            'realised' => $realised,
            'pending' => $pending,
            'by_status' => $byStatus,
            'monthly_realised' => ['labels' => $labels, 'values' => $values],
        ];
    }

    private function specimenStats(int $delegateId): array
    {
        $base = Bss::where('delegate_id', $delegateId);

        $created = (clone $base)->count();
        $delivered = (clone $base)->whereIn('statut', self::BSS_DELIVERED_STATUTS)->count();
        $adopted = (clone $base)->where('statut', 'adopte')->count();
        $retour = (clone $base)->where('statut', 'retour')->count();

        $byStatus = (clone $base)
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        return [
            'created' => $created,
            'delivered' => $delivered,
            'adopted' => $adopted,
            'retour' => $retour,
            'adoption_rate' => $this->rate($adopted, $delivered),
            'retour_rate' => $this->rate($retour, $delivered),
            'by_status' => $byStatus,
        ];
    }

    private function activityStats(int $delegateId): array
    {
        return [
            'examens' => $this->moduleCounts(Examen::query(), 'delegue_id', $delegateId, self::EXAMEN_COMPLETED_STATUTS),
            'formations' => $this->moduleCounts(Formation::query(), 'delegue_id', $delegateId, ['realisee']),
            'events' => $this->eventCounts($delegateId),
            'taches' => $this->moduleCounts(Tache::query(), 'delegue_id', $delegateId, null, 'is_validated', true),
        ];
    }

    private function moduleCounts(
        Builder $query,
        string $ownerColumn,
        int $delegateId,
        ?array $completedStatuts = null,
        ?string $boolColumn = null,
        ?bool $boolCompleted = null
    ): array {
        $base = (clone $query)->where($ownerColumn, $delegateId);
        $created = (clone $base)->count();

        if ($boolColumn !== null) {
            $completed = (clone $base)->where($boolColumn, $boolCompleted)->count();
        } else {
            $completed = (clone $base)->whereIn('statut', $completedStatuts ?? [])->count();
        }

        return ['created' => $created, 'completed' => $completed];
    }

    private function eventCounts(int $delegateId): array
    {
        $base = Event::where('delegue_id', $delegateId);
        $created = (clone $base)->count();
        $completed = (clone $base)->where('date_event', '<', Carbon::today())->count();

        return ['created' => $created, 'completed' => $completed];
    }

    private function validationStatsForDelegates(Collection $delegateIds): array
    {
        if ($delegateIds->isEmpty()) {
            return [
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
            ];
        }

        $base = DemandeSpecimen::whereIn('delegue_id', $delegateIds);

        return [
            'pending' => (clone $base)->where('statut', 'demande')->count(),
            'approved' => (clone $base)->where('statut', 'valide')->count(),
            'rejected' => (clone $base)->where('statut', 'decline')->count(),
        ];
    }

    private function rate(int $numerator, int $denominator): float
    {
        if ($denominator <= 0) {
            return 0.0;
        }

        return round(($numerator / $denominator) * 100, 1);
    }
}
