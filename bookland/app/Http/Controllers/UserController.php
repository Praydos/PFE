<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
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

    // create a new user with role selection and city assignment
    public function create()
    {
        $villes = Ville::all();
        $roles = ['admin', 'rbo', 'delegue', 'abo'];
        $selectedRole = request('role', 'delegue');
        $user = new User(['role' => $selectedRole]); // dummy user for the form
        $assignedVilles = [];
        return view('users.create', compact('villes', 'roles', 'user', 'assignedVilles'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'is_active' => 'boolean',
        'role' => 'required|in:admin,rbo,delegue,abo',
        'ville_id' => 'nullable|exists:villes,id',
        'ville_ids' => 'nullable|array',
        'ville_ids.*' => 'exists:villes,id',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    $user = User::create($validated);

    // Handle RBO ville assignments
    if ($user->role === 'rbo') {
        $villeIds = $request->input('ville_ids', []);
        $user->rboVilles()->sync($villeIds);
    } else {
        $user->rboVilles()->detach(); // optional, but ensures no leftover data
    }

    return redirect()->route('users.index')
        ->with('success', 'Utilisateur créé.');
}
    // edit user with role and city assignment
    public function edit(User $user)
    {
        $villes = Ville::all();
        $roles = ['admin', 'rbo', 'delegue', 'abo'];

        // For RBOs, get the currently assigned villes
        $assignedVilles = $user->role === 'rbo' ? $user->rboVilles->pluck('id')->toArray() : [];

        return view('users.edit', compact('user', 'villes', 'roles', 'assignedVilles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
            'role' => 'required|in:admin,rbo,delegue,abo',
            'ville_id' => 'nullable|exists:villes,id',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        // Handle RBO ville assignments
        if ($user->role === 'rbo') {
            $villeIds = $request->input('ville_ids', []);
            $user->rboVilles()->sync($villeIds);
        } else {
            // Ensure no leftover assignments for non‑RBO users
            $user->rboVilles()->detach();
        }

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour.');
    }
    // delete user with checks for assigned zones and comptes
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Check if user is assigned as rbo to any zone
        if ($user->zonesAsRbo()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est RBO de zones, veuillez d\'abord réassigner ces zones.');
        }

        // Check if user is a delegate assigned to any zone
        if ($user->zones()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est délégué dans des zones, veuillez d\'abord le retirer.');
        }

        // Check if user is delegue on any compte
        if ($user->comptes()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est délégué de comptes, veuillez d\'abord réassigner ces comptes.');
        }

        //check if user is rbo for any city
        if ($user->role === 'rbo' && $user->rboVilles()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur est RBO de villes, veuillez d\'abord réassigner ces villes.');
            }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    //====================================================================================================================
    // New method to show roles and assignments for delegates and RBOs, with search functionality
    public function roles(Request $request)
{
    $delegueSearch = $request->get('delegue_search');
    $rboSearch = $request->get('rbo_search');

    $totalDelegues = User::where('role', 'delegue')->count();
    $totalRbos = User::where('role', 'rbo')->count();

    // Delegates – eager load zones with RBOs
    $delegues = User::with(['ville', 'zones.ville', 'zones.rbo', 'comptes'])
        ->where('role', 'delegue')
        ->when($delegueSearch, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->paginate(10, ['*'], 'delegue_page')
        ->withQueryString();

    // Add a computed attribute for supervising RBOs
    foreach ($delegues as $delegue) {
        $rbos = $delegue->zones->pluck('rbo')->filter()->unique('id');
        $delegue->supervising_rbos = $rbos->map(function($rbo) {
            return $rbo->prenom . ' ' . $rbo->nom;
        })->implode(', ') ?: '—';
    }

    // RBOs – unchanged
    $rbos = User::with(['ville', 'zonesAsRbo.ville', 'zonesAsRbo.delegates', 'rboVilles'])
        ->where('role', 'rbo')
        ->when($rboSearch, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->paginate(5, ['*'], 'rbo_page')
        ->withQueryString();

    return view('users.roles', compact('delegues', 'rbos', 'totalDelegues', 'totalRbos'));
}
  // fetch zones for a user (delegue or rbo) for AJAX requests ========================================================
  

    public function getZones(User $user)
{
    if (!in_array($user->role, ['delegue', 'rbo'])) {
        return response()->json(['error' => 'Invalid user type'], 400);
    }

    if ($user->role === 'delegue') {
        // Delegate: all zones
        $allZones = Zone::with('ville')->get();
        $assignedIds = $user->zones->pluck('id')->toArray();

        // Separate assigned and free
        $assigned = $allZones->whereIn('id', $assignedIds);
        $free = $allZones->whereNotIn('id', $assignedIds);
        $sorted = $assigned->merge($free);
    } else {
        // RBO: only zones they supervise or free zones
        $allZones = Zone::with('ville')
            ->where(function ($query) use ($user) {
                $query->where('rbo_id', $user->id)
                      ->orWhereNull('rbo_id');
            })
            ->get();

        $assigned = $allZones->where('rbo_id', $user->id);
        $free = $allZones->whereNull('rbo_id');
        $sorted = $assigned->merge($free);
    }

    return response()->json([
        'all_zones' => $sorted,
        'assigned_ids' => $user->role === 'delegue'
            ? $user->zones->pluck('id')->toArray()
            : $user->zonesAsRbo->pluck('id')->toArray(),
        'role' => $user->role,
    ]);
}

    public function updateZones(Request $request, User $user)
    {
        if (!in_array($user->role, ['delegue', 'rbo'])) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $request->validate([
            'zone_ids' => 'array',
            'zone_ids.*' => 'exists:zones,id'
        ]);

        if ($user->role === 'delegue') {
            $user->zones()->sync($request->zone_ids ?? []);
        } else { // rbo
            // RBO can be assigned to zones via zone.rbo_id. We'll update those zones.
            // First, remove from all zones where this user is currently RBO
            Zone::where('rbo_id', $user->id)->update(['rbo_id' => null]);
            // Then assign to new zones
            if (!empty($request->zone_ids)) {
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


    // New methods for delegate compte assignments =====================================================
    public function getComptes(User $user)
{
    if ($user->role !== 'delegue') {
        return response()->json(['error' => 'Invalid user type'], 400);
    }

    // Get comptes assigned to this user OR unassigned
    $allComptes = Compte::with(['quartier.zone.ville'])
        ->where(function ($query) use ($user) {
            $query->where('delegue_id', $user->id)
                  ->orWhereNull('delegue_id');
        })
        ->get();

    $assigned = $allComptes->where('delegue_id', $user->id);
    $free = $allComptes->whereNull('delegue_id');
    $sorted = $assigned->merge($free);

    $assignedIds = $user->comptes->pluck('id')->toArray();

    return response()->json([
        'all_comptes' => $sorted,
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
            'compte_ids' => 'array',
            'compte_ids.*' => 'exists:comptes,id',
        ]);

        $newIds = $request->compte_ids ?? [];

        DB::transaction(function () use ($user, $newIds) {
            // Unassign comptes that were assigned to this user but not in new selection
            $user->comptes()
                 ->whereNotIn('id', $newIds)
                 ->update(['delegue_id' => null]);

            // Assign comptes that are in new selection and currently unassigned
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

//=======================================================================
// New methods for RBO ville assignments
public function getVilles(User $user)
{
    if ($user->role !== 'rbo') {
        return response()->json(['error' => 'Seuls les RBOs peuvent avoir des villes assignées.'], 400);
    }

    $allVilles = Ville::all();
    $assignedIds = $user->rboVilles->pluck('id')->toArray();

    $assigned = $allVilles->whereIn('id', $assignedIds);
    $free = $allVilles->whereNotIn('id', $assignedIds);
    $sorted = $assigned->merge($free);

    return response()->json([
        'all_villes' => $sorted,
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
            'ville_ids' => 'array',
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