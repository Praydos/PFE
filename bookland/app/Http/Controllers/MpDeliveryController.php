<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Action;
use App\Models\Compte;
use App\Models\MpDelivery;
use App\Models\MpProduct;
use App\Models\User;
use App\Support\YearLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MpDeliveryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:admin,rbo,delegue,abo')->only(['index', 'show']);
    //     $this->middleware('role:admin,rbo,delegue')->only(['create', 'store']);
    //     $this->middleware('role:admin')->only(['destroy']);
    // }

    private function getCurrentYear(): ?AnneeScolaire
    {
        return AnneeScolaire::where('is_active', true)->first()
            ?? AnneeScolaire::orderByDesc('date_debut')->first();
    }

    private function getPreviousYear(?AnneeScolaire $current): ?AnneeScolaire
    {
        if (! $current) {
            return null;
        }

        return AnneeScolaire::where('date_debut', '<', $current->date_debut)
            ->orderByDesc('date_debut')
            ->first();
    }

    private function allowedCompteIds(\Illuminate\Contracts\Auth\Authenticatable $user): \Illuminate\Support\Collection
    {
        if ($user->role === 'delegue') {
            return Compte::where('delegue_id', $user->id)->pluck('id');
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();

            return Compte::whereIn('delegue_id', $delegateIds)->pluck('id');
        }
        if (in_array($user->role, ['admin', 'abo'], true)) {
            return Compte::query()->pluck('id');
        }

        return collect();
    }

    private function canViewDelivery(\Illuminate\Contracts\Auth\Authenticatable $user, MpDelivery $delivery): bool
    {
        if (in_array($user->role, ['admin', 'abo'], true)) {
            return true;
        }
        if ($user->role === 'delegue') {
            return (int) $delivery->delegate_id === (int) $user->id;
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();

            return $delegateIds->contains($delivery->delegate_id);
        }

        return false;
    }

    private function nextDeliveryNumber(): string
    {
        $year = now()->year;
        $prefix = 'MP-'.$year.'-';
        $last = MpDelivery::where('numero', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();
        $increment = 1;
        if ($last && preg_match('/-(\d{4})$/', $last->numero, $m)) {
            $increment = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $increment, 4, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (! in_array($user->role, ['admin', 'rbo', 'delegue', 'abo'], true)) {
            abort(403);
        }

        $query = MpDelivery::with(['compte', 'contact', 'delegate', 'mpProduct', 'anneeScolaire'])
            ->orderByDesc('date_delivery')
            ->orderByDesc('id');

        if ($user->role === 'delegue') {
            $query->where('delegate_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegate_id', $delegateIds);
        }

        if ($request->filled('compte_id')) {
            $query->where('compte_id', (int) $request->get('compte_id'));
        }
        if ($request->filled('delegate_id') && in_array($user->role, ['admin', 'abo', 'rbo'], true)) {
            $query->where('delegate_id', (int) $request->get('delegate_id'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date_delivery', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_delivery', '<=', $request->get('date_to'));
        }

        $deliveries = $query->paginate(15)->withQueryString();

        $comptesForFilter = Compte::when($user->role === 'delegue', fn ($q) => $q->where('delegue_id', $user->id))
            ->when($user->role === 'rbo', function ($q) use ($user) {
                $ids = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
                $q->whereIn('delegue_id', $ids);
            })
            ->when(in_array($user->role, ['admin', 'abo'], true), fn ($q) => $q)
            ->orderBy('etablissement')
            ->get();

        $delegatesForFilter = collect();
        if (in_array($user->role, ['admin', 'abo'], true)) {
            $delegatesForFilter = User::where('role', 'delegue')->orderBy('nom')->orderBy('prenom')->get();
        } elseif ($user->role === 'rbo') {
            $delegatesForFilter = $user->zonesAsRbo->flatMap->delegates->unique('id')->sortBy('nom')->values();
        }

        return view('mp_deliveries.index', compact('deliveries', 'comptesForFilter', 'delegatesForFilter'));
    }

    public function create()
    {
        $user = Auth::user();
        if (! in_array($user->role, ['admin', 'rbo', 'delegue'], true)) {
            abort(403);
        }

        $currentYear = $this->getCurrentYear();
        if (! $currentYear) {
            return redirect()->route('mp-deliveries.index')
                ->withErrors(['error' => 'Aucune année scolaire configurée.']);
        }

        $compteIds = $this->allowedCompteIds($user);
        $comptes = Compte::whereIn('id', $compteIds)->with('ville')->orderBy('etablissement')->get();
        $products = MpProduct::orderBy('nom')->get();
        $defaultDate = now()->toDateString();

        return view('mp_deliveries.create', compact('comptes', 'products', 'defaultDate', 'currentYear'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (! in_array($user->role, ['admin', 'rbo', 'delegue'], true)) {
            abort(403);
        }

        $allowedCompteIds = $this->allowedCompteIds($user);
        if ($allowedCompteIds->isEmpty()) {
            abort(403, 'Aucun compte disponible pour une livraison MP.');
        }

        $validated = $request->validate([
            'compte_id' => ['required', 'integer', Rule::in($allowedCompteIds->all())],
            'contact_id' => [
                'required',
                'integer',
                Rule::exists('compte_contact', 'contact_id')->where(
                    'compte_id',
                    (int) $request->input('compte_id')
                ),
            ],
            'mp_product_id' => ['required', 'exists:mp_products,id'],
            'date_delivery' => ['required', 'date'],
        ]);

        $compte = Compte::findOrFail($validated['compte_id']);
        $delegateId = (int) $compte->delegue_id;

        $currentYear = $this->getCurrentYear();
        if (! $currentYear) {
            return redirect()->back()->withErrors(['error' => 'Année scolaire active introuvable.'])->withInput();
        }

        $previousYear = $this->getPreviousYear($currentYear);
        $yearIds = array_filter([$currentYear->id, $previousYear?->id]);

        $duplicate = MpDelivery::where('compte_id', $validated['compte_id'])
            ->where('mp_product_id', $validated['mp_product_id'])
            ->whereIn('annee_scolaire_id', $yearIds)
            ->exists();

        if ($duplicate) {
            $curLabel = $currentYear->libelle ?? (string) $currentYear->id;
            $prevLabel = $previousYear ? ($previousYear->libelle ?? (string) $previousYear->id) : '—';

            return redirect()->back()
                ->withErrors([
                    'mp_product_id' => 'Ce matériel pédagogique a déjà été livré à cette école dans l\'année scolaire '
                        .$curLabel.' ou '.$prevLabel.'. Une seule livraison par établissement est autorisée, même pour les années antérieures.',
                ])
                ->withInput();
        }

        return DB::transaction(function () use ($validated, $delegateId, $currentYear) {
            $delivery = MpDelivery::create([
                'numero' => $this->nextDeliveryNumber(),
                'compte_id' => $validated['compte_id'],
                'contact_id' => $validated['contact_id'],
                'delegate_id' => $delegateId,
                'annee_scolaire_id' => $currentYear->id,
                'mp_product_id' => $validated['mp_product_id'],
                'date_delivery' => $validated['date_delivery'],
                'statut' => 'delivered',
            ]);

            return redirect()->route('mp-deliveries.show', $delivery)
                ->with('success', 'Livraison MP enregistrée (n° '.$delivery->numero.').');
        });
    }

    public function show(MpDelivery $mp_delivery)
    {
        $user = Auth::user();
        if (! $this->canViewDelivery($user, $mp_delivery)) {
            abort(403);
        }

        $mp_delivery->load(['compte.ville', 'compte.zone', 'contact', 'delegate', 'mpProduct', 'anneeScolaire', 'linkedCommercialAction']);

        return view('mp_deliveries.show', ['delivery' => $mp_delivery]);
    }

    public function destroy(MpDelivery $mp_delivery)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $mp_delivery->load('anneeScolaire');
        YearLock::check($mp_delivery);

        DB::transaction(function () use ($mp_delivery) {
            Action::where('mp_delivery_id', $mp_delivery->id)->delete();
            Action::where('module_lie', 'mp_delivery')->where('module_id', $mp_delivery->id)->delete();
            $mp_delivery->delete();
        });

        return redirect()->route('mp-deliveries.index')
            ->with('success', 'Livraison MP supprimée.');
    }
}
