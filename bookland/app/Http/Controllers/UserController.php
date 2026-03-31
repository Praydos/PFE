<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Return the IDs of all délégués supervised by an RBO
     * (délégués that belong to at least one of the RBO's zones).
     */
    private function getRboDelegueIds(User $rbo): Collection
    {
        return $rbo->zonesAsRbo()
            ->with('delegates')
            ->get()
            ->flatMap(fn ($zone) => $zone->delegates->pluck('id'))
            ->unique();
    }

    // ── Admin-only CRUD ───────────────────────────────────────────────────────
    // These are behind 'role:admin' middleware in web.php, so no extra checks
    // are needed here. They are unchanged from the original implementation.

    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::with('ville')
            ->when($search, function ($query, $search) {
                return $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhereHas('ville', function ($q) use ($search) {
                        $q->where('nom', 'like', "%{$search}%");
                    });
            })
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $villes       = Ville::all();
        $roles        = ['admin', 'rbo', 'delegue', 'abo'];
        $selectedRole = request('role', 'delegue');
        $user         = new User(['role' => $selectedRole]);
        $assignedVilles = [];

        return view('users.create', compact('villes', 'roles', 'user', 'assignedVilles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'        => 'required|string|max:255',
            'prenom'     => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'is_active'  => 'boolean',
            'role'       => 'required|in:admin,rbo,delegue,abo',
            'ville_id'   => 'nullable|exists:villes,id',
            'ville_ids'  => 'nullable|array',
            'ville_ids.*'=> 'exists:villes,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if ($user->role === 'rbo') {
            $user->rboVilles()->sync($request->input('ville_ids', []));
        } else {
            $user->rboVilles()->detach();
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user)
    {
        $villes         = Ville::all();
        $roles          = ['admin', 'rbo', 'delegue', 'abo'];
        $assignedVilles = $user->role === 'rbo'
            ? $user->rboVilles->pluck('id')->toArray()
            : [];

        return view('users.edit', compact('user', 'villes', 'roles', 'assignedVilles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active'=> 'boolean',
            'role'     => 'required|in:admin,rbo,delegue,abo',
            'ville_id' => 'nullable|exists:villes,id',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if ($user->role === 'rbo') {
            $user->rboVilles()->sync($request->input('ville_ids', []));
        } else {
            $user->rboVilles()->detach();
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->zonesAsRbo()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est RBO de zones, veuillez d\'abord réassigner ces zones.');
        }

        if ($user->zones()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est délégué dans des zones, veuillez d\'abord le retirer.');
        }

        if ($user->comptes()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est délégué de comptes, veuillez d\'abord réassigner ces comptes.');
        }

        if ($user->role === 'rbo' && $user->rboVilles()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est RBO de villes, veuillez d\'abord réassigner ces villes.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    // ── Roles page (admin + rbo + delegue) ────────────────────────────────────

    /**
     * Access matrix for the roles page:
     *
     * Admin   → all délégués + all RBOs, full assignment modals
     * RBO     → only délégués in their zones + only themselves in RBO tab
     *           (assignment modals are hidden in the view — AJAX routes are admin-only)
     * Délégué → only themselves in the délégué tab, RBO tab is empty
     */
    public function roles(Request $request)
    {
        $authUser      = auth()->user();
        $delegueSearch = $request->get('delegue_search');
        $rboSearch     = $request->get('rbo_search');

        // ── Build base queries ───────────────────────────────────────────────
        $delegueQuery = User::with(['ville', 'zones.ville', 'zones.rbo', 'comptes'])
            ->where('role', 'delegue');

        $rboQuery = User::with(['ville', 'zonesAsRbo.ville', 'zonesAsRbo.delegates', 'rboVilles'])
            ->where('role', 'rbo');

        // ── Scope by role ────────────────────────────────────────────────────
        if ($authUser->role === 'rbo') {
            $delegueIds = $this->getRboDelegueIds($authUser);
            $delegueQuery->whereIn('id', $delegueIds);
            $rboQuery->where('id', $authUser->id);   // only themselves

        } elseif ($authUser->role === 'delegue') {
            $delegueQuery->where('id', $authUser->id); // only themselves
            $rboQuery->whereRaw('0 = 1');              // no RBO tab
        }

        // Count before applying search (shows total in scope, not total in DB)
        $totalDelegues = (clone $delegueQuery)->count();
        $totalRbos     = (clone $rboQuery)->count();

        // ── Apply search filters ─────────────────────────────────────────────
        if ($delegueSearch) {
            $delegueQuery->where(function ($q) use ($delegueSearch) {
                $q->where('nom', 'like', "%{$delegueSearch}%")
                  ->orWhere('prenom', 'like', "%{$delegueSearch}%")
                  ->orWhere('email', 'like', "%{$delegueSearch}%");
            });
        }

        if ($rboSearch) {
            $rboQuery->where(function ($q) use ($rboSearch) {
                $q->where('nom', 'like', "%{$rboSearch}%")
                  ->orWhere('prenom', 'like', "%{$rboSearch}%")
                  ->orWhere('email', 'like', "%{$rboSearch}%");
            });
        }

        // ── Paginate ─────────────────────────────────────────────────────────
        $delegues = $delegueQuery
            ->paginate(10, ['*'], 'delegue_page')
            ->withQueryString();

        $rbos = $rboQuery
            ->paginate(5, ['*'], 'rbo_page')
            ->withQueryString();

        // ── Compute supervising RBOs for each délégué ────────────────────────
        foreach ($delegues as $delegue) {
            $rbosList = $delegue->zones->pluck('rbo')->filter()->unique('id');
            $delegue->supervising_rbos = $rbosList
                ->map(fn ($rbo) => $rbo->prenom . ' ' . $rbo->nom)
                ->implode(', ') ?: '—';
        }

        return view('users.roles', compact('delegues', 'rbos', 'totalDelegues', 'totalRbos'));
    }

    // ── Admin-only AJAX: zone assignments ─────────────────────────────────────

    public function getZones(User $user)
    {
        if (! in_array($user->role, ['delegue', 'rbo'])) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        if ($user->role === 'delegue') {
            $allZones   = Zone::with('ville')->get();
            $assignedIds = $user->zones->pluck('id')->toArray();
            $assigned   = $allZones->whereIn('id', $assignedIds);
            $free       = $allZones->whereNotIn('id', $assignedIds);
            $sorted     = $assigned->merge($free);
        } else {
            $allZones = Zone::with('ville')
                ->where(function ($query) use ($user) {
                    $query->where('rbo_id', $user->id)->orWhereNull('rbo_id');
                })
                ->get();

            $assigned = $allZones->where('rbo_id', $user->id);
            $free     = $allZones->whereNull('rbo_id');
            $sorted   = $assigned->merge($free);
        }

        return response()->json([
            'all_zones'    => $sorted,
            'assigned_ids' => $user->role === 'delegue'
                ? $user->zones->pluck('id')->toArray()
                : $user->zonesAsRbo->pluck('id')->toArray(),
            'role' => $user->role,
        ]);
    }

    public function updateZones(Request $request, User $user)
    {
        if (! in_array($user->role, ['delegue', 'rbo'])) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $request->validate([
            'zone_ids'   => 'array',
            'zone_ids.*' => 'exists:zones,id',
        ]);

        if ($user->role === 'delegue') {
            $user->zones()->sync($request->zone_ids ?? []);
        } else {
            Zone::where('rbo_id', $user->id)->update(['rbo_id' => null]);
            if (! empty($request->zone_ids)) {
                Zone::whereIn('id', $request->zone_ids)->update(['rbo_id' => $user->id]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function getAssignedZones(User $user)
    {
        if ($user->role !== 'delegue') {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $zones = $user->zones()->with(['ville', 'rbo'])->get();
        return response()->json(['zones' => $zones]);
    }

    // ── Admin-only AJAX: compte assignments ───────────────────────────────────

    public function getComptes(User $user)
    {
        if ($user->role !== 'delegue') {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $allComptes = Compte::with(['quartier.zone.ville'])
            ->where(function ($query) use ($user) {
                $query->where('delegue_id', $user->id)->orWhereNull('delegue_id');
            })
            ->get();

        $assigned   = $allComptes->where('delegue_id', $user->id);
        $free       = $allComptes->whereNull('delegue_id');
        $sorted     = $assigned->merge($free);
        $assignedIds = $user->comptes->pluck('id')->toArray();

        return response()->json([
            'all_comptes'  => $sorted,
            'assigned_ids' => $assignedIds,
        ]);
    }

    public function updateComptes(Request $request, User $user)
    {
        try {
            if ($user->role !== 'delegue') {
                return response()->json(['error' => 'L\'utilisateur n\'est pas un délégué.'], 400);
            }

            $request->validate([
                'compte_ids'   => 'array',
                'compte_ids.*' => 'exists:comptes,id',
            ]);

            $newIds = $request->compte_ids ?? [];

            DB::transaction(function () use ($user, $newIds) {
                $user->comptes()
                     ->whereNotIn('id', $newIds)
                     ->update(['delegue_id' => null]);

                Compte::whereIn('id', $newIds)
                      ->whereNull('delegue_id')
                      ->update(['delegue_id' => $user->id]);
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error updating comptes: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ── Admin-only AJAX: ville assignments for RBOs ───────────────────────────

    public function getVilles(User $user)
    {
        if ($user->role !== 'rbo') {
            return response()->json(['error' => 'Seuls les RBOs peuvent avoir des villes assignées.'], 400);
        }

        $allVilles   = Ville::all();
        $assignedIds = $user->rboVilles->pluck('id')->toArray();
        $assigned    = $allVilles->whereIn('id', $assignedIds);
        $free        = $allVilles->whereNotIn('id', $assignedIds);
        $sorted      = $assigned->merge($free);

        return response()->json([
            'all_villes'   => $sorted,
            'assigned_ids' => $assignedIds,
        ]);
    }

    public function updateVilles(Request $request, User $user)
    {
        if ($user->role !== 'rbo') {
            return response()->json(['error' => 'Seuls les RBOs peuvent avoir des villes assignées.'], 400);
        }

        try {
            $request->validate([
                'ville_ids'   => 'array',
                'ville_ids.*' => 'exists:villes,id',
            ]);

            $user->rboVilles()->sync($request->ville_ids ?? []);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error updating villes for RBO ' . $user->id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }
}