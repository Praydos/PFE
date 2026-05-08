<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Examen;
use App\Models\Formation;
use App\Models\Event;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tache;
use Illuminate\Support\Facades\Log;
use App\Models\Bss;
use Carbon\Carbon;

class AgendaController extends Controller
{
    // ── Role scoping helpers ──────────────────────────────────────────────

    private function getDelegateIdsForRbo($user)
    {
        return $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
    }

    /**
     * Apply the correct scope to any query based on user role.
     * Admin and ABO see everything. RBO sees their delegates. Delegué sees themselves.
     */
    private function scopeByRole($query, $user, string $foreignKey = 'delegue_id')
    {
        if (in_array($user->role, ['admin', 'abo'])) {
            // No filter — see all
        } elseif ($user->role === 'rbo') {
            $delegateIds = $this->getDelegateIdsForRbo($user);
            $query->whereIn($foreignKey, $delegateIds);
        } else {
            // delegue
            $query->where($foreignKey, $user->id);
        }
        return $query;
    }

    // ── Public actions ────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user    = Auth::user();
        $viewMode = $request->get('view', 'calendar');
        $tab     = $request->get('tab', 'all');

        // Stat counts for the header cards
        $stats = $this->getStats($user);

        $events = collect();
        if ($viewMode === 'list') {
            $events = $this->getListEvents($user, $tab, $request);
        }

        return view('agenda.index', compact('viewMode', 'tab', 'events', 'stats'));
    }

    public function events(Request $request)
    {
        try {
            $user  = Auth::user();
            $start = $request->input('start');
            $end   = $request->input('end');
            $tab   = $request->get('tab', 'all');

            $calendarEvents = [];

            if (in_array($tab, ['all', 'actions', 'tasks'])) {
                $filterType = ($tab === 'tasks') ? 'tache' : (($tab === 'actions') ? 'commercial' : null);
                foreach ($this->getActions($user, $start, $end, $filterType) as $action) {
                    if ($e = $this->formatActionEvent($action)) $calendarEvents[] = $e;
                }
            }

            if (in_array($tab, ['all', 'examens'])) {
                foreach ($this->getExamens($user, $start, $end) as $examen) {
                    if ($e = $this->formatExamenEvent($examen)) $calendarEvents[] = $e;
                }
            }

            if (in_array($tab, ['all', 'formations'])) {
                foreach ($this->getFormations($user, $start, $end) as $formation) {
                    if ($e = $this->formatFormationEvent($formation)) $calendarEvents[] = $e;
                }
            }

            if (in_array($tab, ['all', 'events'])) {
                foreach ($this->getEvents($user, $start, $end) as $event) {
                    if ($e = $this->formatEventEvent($event)) $calendarEvents[] = $e;
                }
            }

            if (in_array($tab, ['all', 'specimens'])) {
                foreach ($this->getSpecimens($user, $start, $end) as $bss) {
                    if ($e = $this->formatSpecimenEvent($bss)) $calendarEvents[] = $e;
                }
            }

            if (in_array($tab, ['all', 'tasks'])) {
                foreach ($this->getTasks($user, $start, $end) as $task) {
                    if ($e = $this->formatTaskEvent($task)) $calendarEvents[] = $e;
                }
            }

            // Vacation backgrounds (everyone sees them)
            if ($tab === 'all') {
                foreach (Vacation::all() as $vacation) {
                    $calendarEvents[] = [
                        'title'   => $vacation->name,
                        'start'   => $vacation->start_date->toDateString(),
                        'end'     => $vacation->end_date->toDateString(),
                        'display' => 'background',
                        'color'   => '#f8d7da',
                        'textColor' => '#721c24',
                    ];
                }
            }

            return response()->json($calendarEvents);

        } catch (\Exception $e) {
            Log::error('Agenda events error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function rescheduleEvent(Request $request, $type, $id)
    {
        $user    = Auth::user();
        $newDate = $request->input('new_date');

        if (!$newDate) {
            return response()->json(['error' => 'Date manquante'], 422);
        }

        $model = null;
        switch ($type) {
            case 'action':   $model = Action::findOrFail($id);  $model->date_planification   = $newDate; break;
            case 'examen':   $model = Examen::findOrFail($id);  $model->date_examen           = $newDate; break;
            case 'event':    $model = Event::findOrFail($id);   $model->date_event            = $newDate; break;
            case 'specimen': $model = Bss::findOrFail($id);     $model->date_livraison_prevue = $newDate; break;
            case 'tache':    $model = Tache::findOrFail($id);   $model->date_planification    = $newDate; break;
            default:         return response()->json(['error' => 'Type non supporté'], 400);
        }

        // Authorization: only admin/abo can move anything; others only their own items
        $ownerField = ($type === 'specimen') ? 'delegate_id' : 'delegue_id';
        if (!in_array($user->role, ['admin', 'abo']) && $model->$ownerField !== $user->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $model->save();
        return response()->json(['success' => true]);
    }

    // ── Stat card counts ──────────────────────────────────────────────────

    private function getStats($user): array
    {
        $today = Carbon::today();
        $weekEnd = Carbon::today()->endOfWeek();

        return [
            'actions_week'   => $this->scopeByRole(Action::query(), $user)->whereBetween('date_planification', [$today, $weekEnd])->count(),
            'examens_month'  => $this->scopeByRole(Examen::query(), $user)->whereMonth('date_examen', $today->month)->count(),
            'formations_total' => $this->scopeByRole(Formation::query(), $user)->count(),
            'events_month'   => $this->scopeByRole(Event::query(), $user)->whereMonth('date_event', $today->month)->count(),
            'tasks_pending'  => $this->scopeByRole(Tache::query(), $user)->where('is_validated', false)->count(),
        ];
    }

    // ── List view (previously stubbed — now implemented) ──────────────────

    private function getListEvents($user, $tab, $request)
    {
        $perPage  = 15;
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $allItems = collect();

        // Actions
        if (in_array($tab, ['all', 'actions', 'tasks'])) {
            $query = $this->scopeByRole(
                Action::with(['compte.ville', 'compte.zone', 'delegate']), $user
            );
            if ($dateFrom) $query->where('date_planification', '>=', $dateFrom);
            if ($dateTo)   $query->where('date_planification', '<=', $dateTo);
            if ($tab === 'tasks')   $query->where('type', 'tache');
            if ($tab === 'actions') $query->where('type', 'commercial');

            $query->get()->each(function ($a) use (&$allItems) {
                if (!$a->compte) return;
                $allItems->push([
                    'date'     => $a->date_planification,
                    'heure'    => $a->heure ? substr($a->heure, 0, 5) : null,
                    'title'    => ($a->objet ?? 'Action') . ' – ' . ($a->compte->etablissement ?? ''),
                    'type'     => 'actions',
                    'compte'   => $a->compte->etablissement ?? '',
                    'ville'    => $a->compte->ville->nom ?? '',
                    'zone'     => $a->compte->zone->name ?? '',
                    'delegate' => $a->delegate ? trim($a->delegate->prenom . ' ' . $a->delegate->nom) : '—',
                    'url'      => route('actions.show', $a),
                ]);
            });
        }

        // Examens
        if (in_array($tab, ['all', 'examens'])) {
            $query = $this->scopeByRole(
                Examen::with(['compte.ville', 'compte.zone', 'delegate'])->whereNotNull('date_examen'), $user
            );
            if ($dateFrom) $query->where('date_examen', '>=', $dateFrom);
            if ($dateTo)   $query->where('date_examen', '<=', $dateTo);

            $query->get()->each(function ($ex) use (&$allItems) {
                if (!$ex->compte) return;
                $allItems->push([
                    'date'     => $ex->date_examen,
                    'heure'    => null,
                    'title'    => 'Examen: ' . ($ex->titre ?? '') . ' – ' . ($ex->compte->etablissement ?? ''),
                    'type'     => 'examens',
                    'compte'   => $ex->compte->etablissement ?? '',
                    'ville'    => $ex->compte->ville->nom ?? '',
                    'zone'     => $ex->compte->zone->name ?? '',
                    'delegate' => $ex->delegate ? trim($ex->delegate->prenom . ' ' . $ex->delegate->nom) : '—',
                    'url'      => route('examens.show', $ex),
                ]);
            });
        }

        // Formations
        if (in_array($tab, ['all', 'formations'])) {
            $query = $this->scopeByRole(
                Formation::with(['compte.ville', 'compte.zone', 'delegate']), $user
            );
            $query->get()->each(function ($f) use (&$allItems, $dateFrom, $dateTo) {
                $dates = $f->dates_proposees ?? [];
                if (empty($dates)) return;
                $first = Carbon::parse(reset($dates));
                if ($dateFrom && $first->lt($dateFrom)) return;
                if ($dateTo   && $first->gt($dateTo))   return;
                if (!$f->compte) return;
                $allItems->push([
                    'date'     => $first,
                    'heure'    => null,
                    'title'    => 'Formation: ' . ($f->type ?? '') . ' – ' . ($f->compte->etablissement ?? ''),
                    'type'     => 'formations',
                    'compte'   => $f->compte->etablissement ?? '',
                    'ville'    => $f->compte->ville->nom ?? '',
                    'zone'     => $f->compte->zone->name ?? '',
                    'delegate' => $f->delegate ? trim($f->delegate->prenom . ' ' . $f->delegate->nom) : '—',
                    'url'      => route('formations.show', $f),
                ]);
            });
        }

        // Events
        if (in_array($tab, ['all', 'events'])) {
            $query = $this->scopeByRole(
                Event::with(['ville', 'zone', 'delegate'])->whereNotNull('date_event'), $user
            );
            if ($dateFrom) $query->where('date_event', '>=', $dateFrom);
            if ($dateTo)   $query->where('date_event', '<=', $dateTo);

            $query->get()->each(function ($ev) use (&$allItems) {
                $allItems->push([
                    'date'     => $ev->date_event,
                    'heure'    => null,
                    'title'    => 'Événement: ' . ($ev->type ?? '') . ' – ' . ($ev->ville->nom ?? ''),
                    'type'     => 'events',
                    'compte'   => $ev->ville->nom ?? '',
                    'ville'    => $ev->ville->nom ?? '',
                    'zone'     => $ev->zone->name ?? '',
                    'delegate' => $ev->delegate ? trim($ev->delegate->prenom . ' ' . $ev->delegate->nom) : '—',
                    'url'      => route('events.show', $ev),
                ]);
            });
        }

        // Tâches
        if (in_array($tab, ['all', 'tasks'])) {
            $query = $this->scopeByRole(Tache::with('delegate'), $user);
            if ($dateFrom) $query->where('date_planification', '>=', $dateFrom);
            if ($dateTo)   $query->where('date_planification', '<=', $dateTo);

            $query->get()->each(function ($t) use (&$allItems) {
                if (!$t->delegate) return;
                $allItems->push([
                    'date'     => Carbon::parse($t->date_planification),
                    'heure'    => $t->heure ? substr($t->heure, 0, 5) : null,
                    'title'    => $t->objet ?? 'Tâche',
                    'type'     => 'tasks',
                    'compte'   => '',
                    'ville'    => '',
                    'zone'     => '',
                    'delegate' => trim(($t->delegate->prenom ?? '') . ' ' . ($t->delegate->nom ?? '')),
                    'url'      => route('taches.show', $t),
                ]);
            });
        }

        // Sort all items by date, paginate manually
        $sorted = $allItems->sortBy('date')->values();
        $page   = $request->get('page', 1);
        $slice  = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator($slice, $sorted->count(), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    // ── Data fetchers ─────────────────────────────────────────────────────

    private function getActions($user, $start, $end, $type = null)
    {
        $query = $this->scopeByRole(Action::with(['compte.ville', 'compte.zone', 'delegate']), $user);
        if ($start && $end) $query->whereBetween('date_planification', [$start, $end]);
        if ($type)          $query->where('type', $type);
        return $query->orderBy('date_planification')->get();
    }

    private function getExamens($user, $start, $end)
    {
        $query = $this->scopeByRole(
            Examen::with(['compte.ville', 'compte.zone', 'delegate'])->whereNotNull('date_examen'), $user
        );
        if ($start && $end) $query->whereBetween('date_examen', [$start, $end]);
        return $query->orderBy('date_examen')->get();
    }

    private function getFormations($user, $start, $end)
    {
        $formations = $this->scopeByRole(Formation::with(['compte.ville', 'compte.zone', 'delegate']), $user)->get();
        return $formations->filter(function ($f) use ($start, $end) {
            $dates = $f->dates_proposees ?? [];
            if (empty($dates)) return false;
            $first = reset($dates);
            return !($start && $end) || ($first >= $start && $first <= $end);
        })->values();
    }

    private function getEvents($user, $start, $end)
    {
        $query = $this->scopeByRole(Event::with(['ville', 'zone', 'delegate']), $user);
        if ($start && $end) $query->whereBetween('date_event', [$start, $end]);
        return $query->orderBy('date_event')->get();
    }

    private function getSpecimens($user, $start, $end)
    {
        // NOTE: verify your bss migration — is it delegate_id or delegue_id?
        $query = $this->scopeByRole(
            Bss::with(['compte.ville', 'compte.zone', 'delegate'])->whereNotNull('date_livraison_prevue'),
            $user,
            'delegate_id'   // ← change to 'delegue_id' if your bss table uses that
        );
        if ($start && $end) $query->whereBetween('date_livraison_prevue', [$start, $end]);
        return $query->orderBy('date_livraison_prevue')->get();
    }

    private function getTasks($user, $start, $end)
    {
        $query = $this->scopeByRole(Tache::with('delegate'), $user);
        if ($start && $end) $query->whereBetween('date_planification', [$start, $end]);
        return $query->get();
    }

    // ── Formatters (with null-safe delegate) ──────────────────────────────

    private function delegateName($model): string
    {
        return $model->delegate
            ? trim(($model->delegate->prenom ?? '') . ' ' . ($model->delegate->nom ?? ''))
            : '—';
    }

    private function formatActionEvent($action)
    {
        if (!$action->compte) return null;
        return [
            'id'    => $action->id,
            'title' => ($action->objet ?? 'Action') . ' – ' . ($action->compte->etablissement ?? ''),
            'start' => $action->date_planification->toDateString() . ($action->heure ? 'T' . $action->heure : ''),
            'url'   => route('actions.show', $action),
            'color' => $this->getColorForDelegate($action->delegue_id),
            'extendedProps' => [
                'type'     => 'action',
                'compte'   => $action->compte->etablissement ?? '',
                'ville'    => $action->compte->ville->nom ?? '',
                'zone'     => $action->compte->zone->name ?? '',
                'delegate' => $this->delegateName($action),
            ],
        ];
    }

    private function formatExamenEvent($examen)
    {
        if (!$examen->compte || !$examen->date_examen) return null;
        return [
            'id'    => $examen->id,
            'title' => 'Examen: ' . ($examen->titre ?? '') . ' – ' . ($examen->compte->etablissement ?? ''),
            'start' => $examen->date_examen->toDateString(),
            'url'   => route('examens.show', $examen),
            'color' => '#6f42c1',
            'extendedProps' => [
                'type'     => 'examen',
                'compte'   => $examen->compte->etablissement ?? '',
                'ville'    => $examen->compte->ville->nom ?? '',
                'zone'     => $examen->compte->zone->name ?? '',
                'delegate' => $this->delegateName($examen),
            ],
        ];
    }

    private function formatFormationEvent($formation)
    {
        if (!$formation->compte) return null;
        $dates = $formation->dates_proposees ?? [];
        if (empty($dates)) return null;
        $firstDate = reset($dates);
        if (!$firstDate) return null;
        return [
            'id'    => $formation->id,
            'title' => 'Formation: ' . ($formation->type ?? '') . ' – ' . ($formation->compte->etablissement ?? ''),
            'start' => $firstDate,
            'url'   => route('formations.show', $formation),
            'color' => '#fd7e14',
            'extendedProps' => [
                'type'     => 'formation',
                'compte'   => $formation->compte->etablissement ?? '',
                'ville'    => $formation->compte->ville->nom ?? '',
                'zone'     => $formation->compte->zone->name ?? '',
                'delegate' => $this->delegateName($formation),
            ],
        ];
    }

    private function formatEventEvent($event)
    {
        if (!$event->date_event) return null;
        return [
            'id'    => $event->id,
            'title' => 'Événement: ' . ($event->type ?? '') . ' – ' . ($event->ville->nom ?? ''),
            'start' => $event->date_event->toDateString(),
            'url'   => route('events.show', $event),
            'color' => '#28a745',
            'extendedProps' => [
                'type'     => 'event',
                'compte'   => $event->ville->nom ?? '',
                'ville'    => $event->ville->nom ?? '',
                'zone'     => $event->zone->name ?? '',
                'delegate' => $this->delegateName($event),
            ],
        ];
    }

    private function formatSpecimenEvent($bss)
    {
        if (!$bss->compte) return null;
        return [
            'id'    => $bss->id,
            'title' => 'Livraison BSS: ' . ($bss->numero ?? '') . ' – ' . ($bss->compte->etablissement ?? ''),
            'start' => $bss->date_livraison_prevue->toDateString(),
            'url'   => route('bss.show', $bss),
            'color' => '#17a2b8',
            'extendedProps' => [
                'type'     => 'specimen',
                'compte'   => $bss->compte->etablissement ?? '',
                'ville'    => $bss->compte->ville->nom ?? '',
                'zone'     => $bss->compte->zone->name ?? '',
                'delegate' => $this->delegateName($bss),
            ],
        ];
    }

    private function formatTaskEvent($task)
    {
        if (!$task->delegate) return null;
        $dateStr = $task->date_planification instanceof Carbon
            ? $task->date_planification->toDateString()
            : Carbon::parse($task->date_planification)->toDateString();
        $heure = $task->heure ? substr($task->heure, 0, 5) : null;
        return [
            'id'    => $task->id,
            'title' => $task->objet ?? 'Tâche',
            'start' => $dateStr . ($heure ? 'T' . $heure : ''),
            'url'   => route('taches.show', $task),
            'color' => '#9ba8c5',
            'extendedProps' => [
                'type'     => 'task',
                'delegate' => $this->delegateName($task),
            ],
        ];
    }

    private function getColorForDelegate($delegateId): string
    {
        $hue = hexdec(substr(hash('md5', $delegateId), 0, 6)) % 360;
        return "hsl($hue, 70%, 50%)";
    }
}