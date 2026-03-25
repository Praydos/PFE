<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ville;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $selectedRole = request('role', 'delegue'); // default to delegue
        return view('users.create', compact('villes', 'roles', 'selectedRole'));
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
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé.');
    }
    // edit user with role and city assignment
    public function edit(User $user)
    {
        $villes = Ville::all();
        $roles = ['admin', 'rbo', 'delegue', 'abo'];
        return view('users.edit', compact('user', 'villes', 'roles'));
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

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour.');
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

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé.');
    }


    // New method to show roles and assignments
    public function roles()
{
    // Get all delegates with their city and assigned zones (with city)
    $delegues = User::with(['ville', 'zones.ville'])
        ->where('role', 'delegue')
        ->get();

    // Get all RBOs with their city, the zones they supervise (with city), and delegates in those zones
    $rbos = User::with(['ville', 'zonesAsRbo.ville', 'zonesAsRbo.delegates'])
        ->where('role', 'rbo')
        ->get();

    return view('users.roles', compact('delegues', 'rbos'));
} 

  // fetch zones for a user (delegue or rbo) for AJAX requests
  

    public function getZones(User $user)
    {
        // Only for delegates and RBOs
        if (!in_array($user->role, ['delegue', 'rbo'])) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $allZones = Zone::with('ville')->get();
        $assignedZoneIds = $user->role === 'delegue' 
            ? $user->zones->pluck('id')->toArray() 
            : $user->zonesAsRbo->pluck('id')->toArray();

        return response()->json([
            'all_zones' => $allZones,
            'assigned_ids' => $assignedZoneIds,
            'role' => $user->role
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




}