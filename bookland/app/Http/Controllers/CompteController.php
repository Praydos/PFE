<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use App\Models\Quartier;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CompteController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Return the IDs of all délégués supervised by the given RBO
     * (i.e. délégués that belong to at least one of the RBO's zones).
     */
    private function getRboDelegueIds(User $rbo): Collection
    {
        return $rbo->zonesAsRbo()
            ->with('delegates')
            ->get()
            ->flatMap(fn ($zone) => $zone->delegates->pluck('id'))
            ->unique();
    }

    /**
     * Abort if the authenticated user has no right to act on $compte.
     *   - Admin  → always allowed
     *   - RBO    → compte must belong to one of their délégués
     *   - Délégué → compte must be assigned to themselves
     */
    private function authorizeCompte(User $user, Compte $compte): void
    {
        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'delegue' && $compte->delegue_id !== $user->id) {
            abort(403);
        }

        if ($user->role === 'rbo') {
            if (! $this->getRboDelegueIds($user)->contains($compte->delegue_id)) {
                abort(403);
            }
        }
    }

    // ── CRUD ──────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Compte::with(['ville', 'zone', 'delegue']);

        // Scope results by role
        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);

        } elseif ($user->role === 'rbo') {
            $delegueIds = $this->getRboDelegueIds($user);
            $query->whereIn('delegue_id', $delegueIds);
        }
        // Admin sees all

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('etablissement', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('ville', fn ($q) => $q->where('nom', 'like', "%{$search}%"))
                  ->orWhereHas('zone', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $comptes = $query->paginate(15);
        return view('comptes.index', compact('comptes'));
    }

    public function create()
    {
        $user = auth()->user();

        // Délégués cannot create comptes
        if ($user->role === 'delegue') {
            return redirect()->route('comptes.index')
                ->with('error', 'Vous n\'avez pas la permission de créer des comptes.');
        }

        [$villes, $zones, $delegues] = $this->formOptionsFor($user);

        $quartiers = Quartier::with('zone')->get();
        $types     = ['ecole', 'centre_de_langue', 'librairie', 'autre'];
        $cycles    = ['Maternelle', 'Primaire', 'Collège', 'Lycée', 'Kids', 'Teens', 'Adults'];
        $statuses  = ['actif', 'ferme'];

        return view('comptes.create', compact(
            'villes', 'zones', 'delegues', 'quartiers', 'types', 'cycles', 'statuses'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'delegue') {
            abort(403);
        }

        $validated = $request->validate([
            'type'             => 'required|in:ecole,centre_de_langue,librairie,autre',
            'etablissement'    => 'required|string|max:255',
            'ville_id'         => 'required|exists:villes,id',
            'zone_id'          => 'required|exists:zones,id',
            'quartier_id'      => 'nullable|exists:quartiers,id',
            'adresse'          => 'required|string',
            'delegue_id'       => 'required|exists:users,id',
            'status'           => 'required|in:actif,ferme',
            'motif_fermeture'  => 'nullable|required_if:status,ferme|string',
            'cycle'            => 'nullable|in:Maternelle,Primaire,Collège,Lycée,Kids,Teens,Adults',
            'tel_bureau_1'     => 'nullable|string|max:20',
            'email'            => 'nullable|email',
        ]);

        // Extra scope checks for RBO
        if ($user->role === 'rbo') {
            if (! $user->zonesAsRbo->contains('id', $validated['zone_id'])) {
                return back()
                    ->withErrors(['zone_id' => 'Cette zone ne fait pas partie de vos zones.'])
                    ->withInput();
            }
            if (! $this->getRboDelegueIds($user)->contains($validated['delegue_id'])) {
                return back()
                    ->withErrors(['delegue_id' => 'Ce délégué ne fait pas partie de vos délégués.'])
                    ->withInput();
            }
        }

        // Ensure the delegate is actually assigned to the chosen zone
        $zone = Zone::with('delegates')->find($validated['zone_id']);
        if (! $zone->delegates->contains('id', $validated['delegue_id'])) {
            return back()
                ->withErrors(['delegue_id' => 'Le délégué sélectionné n\'est pas assigné à cette zone.'])
                ->withInput();
        }

        Compte::create($validated);

        return redirect()->route('comptes.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function edit(Compte $compte)
    {
        $user = auth()->user();
        $this->authorizeCompte($user, $compte);

        [$villes, $zones, $delegues] = $this->formOptionsFor($user);

        $quartiers = Quartier::with('zone')->get();
        $types     = ['ecole', 'centre_de_langue', 'librairie', 'autre'];
        $cycles    = ['Maternelle', 'Primaire', 'Collège', 'Lycée', 'Kids', 'Teens', 'Adults'];
        $statuses  = ['actif', 'ferme'];

        return view('comptes.edit', compact(
            'compte', 'villes', 'zones', 'delegues', 'types', 'cycles', 'statuses', 'quartiers'
        ));
    }

    public function update(Request $request, Compte $compte)
    {
        $user = auth()->user();
        $this->authorizeCompte($user, $compte);

        // ── Délégué: restricted update (operational fields only) ─────────────
        if ($user->role === 'delegue') {
            $validated = $request->validate([
                'adresse'          => 'required|string',
                'tel_bureau_1'     => 'nullable|string|max:20',
                'email'            => 'nullable|email',
                'status'           => 'required|in:actif,ferme',
                'motif_fermeture'  => 'nullable|required_if:status,ferme|string',
            ]);

            $compte->update($validated);

            return redirect()->route('comptes.index')
                ->with('success', 'Compte mis à jour.');
        }

        // ── Admin / RBO: full update ──────────────────────────────────────────
        $validated = $request->validate([
            'type'             => 'required|in:ecole,centre_de_langue,librairie,autre',
            'etablissement'    => 'required|string|max:255',
            'ville_id'         => 'required|exists:villes,id',
            'zone_id'          => 'required|exists:zones,id',
            'adresse'          => 'required|string',
            'delegue_id'       => 'required|exists:users,id',
            'status'           => 'required|in:actif,ferme',
            'motif_fermeture'  => 'nullable|required_if:status,ferme|string',
            'cycle'            => 'nullable|in:Maternelle,Primaire,Collège,Lycée,Kids,Teens,Adults',
            'tel_bureau_1'     => 'nullable|string|max:20',
            'email'            => 'nullable|email',
        ]);

        // Extra scope checks for RBO
        if ($user->role === 'rbo') {
            if (! $user->zonesAsRbo->contains('id', $validated['zone_id'])) {
                return back()
                    ->withErrors(['zone_id' => 'Cette zone ne fait pas partie de vos zones.'])
                    ->withInput();
            }
            if (! $this->getRboDelegueIds($user)->contains($validated['delegue_id'])) {
                return back()
                    ->withErrors(['delegue_id' => 'Ce délégué ne fait pas partie de vos délégués.'])
                    ->withInput();
            }
        }

        $zone = Zone::with('delegates')->find($validated['zone_id']);
        if (! $zone->delegates->contains('id', $validated['delegue_id'])) {
            return back()
                ->withErrors(['delegue_id' => 'Le délégué sélectionné n\'est pas assigné à cette zone.'])
                ->withInput();
        }

        $compte->update($validated);

        return redirect()->route('comptes.index')
            ->with('success', 'Compte mis à jour.');
    }

    public function destroy(Compte $compte)
    {
        $user = auth()->user();

        // Délégués cannot delete
        if ($user->role === 'delegue') {
            abort(403, 'Les délégués ne peuvent pas supprimer des comptes.');
        }

        $this->authorizeCompte($user, $compte);

        $compte->delete();

        return redirect()->route('comptes.index')
            ->with('success', 'Compte supprimé.');
    }


    public function show(Compte $compte)
    {
        $user = auth()->user();
        $this->authorizeCompte($user, $compte);

        // Load relationships
        $compte->load(['delegue', 'ville', 'zone', 'quartier', 'contacts']);

        // Load effectifs with their year and sources
        $effectifs = $compte->effectifs()
            ->with(['anneeScolaire', 'sourceContact1', 'sourceContact2', 'sourceContact3'])
            ->orderBy('annee_scolaire_id', 'desc')
            ->orderBy('niveau')
            ->get();

        // Group effectifs by year for easier display
        $effectifsByYear = $effectifs->groupBy('annee_scolaire_id');

        // Get all years for a selector (to view effectifs by year)
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();

        // Current active year (for taille calculation)
        $currentYear = AnneeScolaire::where('is_active', true)->first();

        return view('comptes.show', compact('compte', 'effectifsByYear', 'years', 'currentYear'));
    }

    // ── Private helper ────────────────────────────────────────────────────────

    /**
     * Return [villes, zones, delegues] collections scoped to the user's role.
     * Used by both create() and edit() to populate form dropdowns.
     */
    private function formOptionsFor(User $user): array
    {
        if ($user->role === 'rbo') {
            $zones      = $user->zonesAsRbo()->with('ville')->get();
            $delegueIds = $this->getRboDelegueIds($user);
            $delegues   = User::whereIn('id', $delegueIds)->get();
            $villes     = $zones->map(fn ($z) => $z->ville)->filter()->unique('id')->values();
        } else {
            // Admin
            $villes   = Ville::all();
            $zones    = Zone::all();
            $delegues = User::where('role', 'delegue')->get();
        }

        return [$villes, $zones, $delegues];
    }

    // ── get the taille of the niveau for the compte client  ─────────────────
   
}