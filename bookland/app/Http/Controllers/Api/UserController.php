<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Admin only (enforced in routes)
        $query = User::query();
        if ($request->filled('role'))
            $query->where('role', $request->role);
        return response()->json($query->latest()->paginate(50));
    }

    public function show(User $user)
    {
        $this->gateAdminOrSelf($user);
        $user->load('ville');
        return response()->json($user->makeHidden(['password', 'remember_token']));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,rbo,delegue,abo',
            'ville_id' => 'nullable|exists:villes,id',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return response()->json($user->makeHidden(['password', 'remember_token']), 201);
    }

    public function update(Request $request, User $user)
    {
        $this->gateAdminOrSelf($user);
        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,rbo,delegue,abo',
            'ville_id' => 'nullable|exists:villes,id',
            'is_active' => 'sometimes|boolean',
        ]);
        if (isset($data['password']))
            $data['password'] = Hash::make($data['password']);
        $user->update($data);
        return response()->json($user->makeHidden(['password', 'remember_token']));
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $user->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function getAssignedComptes(User $user)
    {
        $this->gateAdminOrSelf($user);
        return response()->json($user->comptes()->with(['ville', 'zone'])->get());
    }

    public function getAssignedZones(User $user)
    {
        $this->gateAdminOrSelf($user);
        return response()->json($user->zones()->get());
    }

    private function gateAdminOrSelf(User $user): void
    {
        $auth = Auth::user();
        if ($auth->role === 'admin')
            return;
        if ($auth->id === $user->id)
            return;
        if ($auth->role === 'rbo') {
            $delegueIds = $auth->supervisedDelegates()->pluck('id');
            if ($delegueIds->contains($user->id))
                return;
        }
        abort(403);
    }
}
