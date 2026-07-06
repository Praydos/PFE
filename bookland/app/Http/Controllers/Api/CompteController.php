<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompteController extends Controller
{
    private function authorizeCompte(User $user, Compte $compte)
    {
        if ($user->role === 'admin')
            return;
        if ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            if (!$delegueIds->contains($compte->delegue_id))
                abort(403);
            return;
        }
        if ($user->role === 'delegue') {
            if ($compte->delegue_id !== $user->id)
                abort(403);
            return;
        }
        abort(403);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Compte::with(['ville', 'zone', 'delegue']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $query->whereIn('delegue_id', $delegueIds);
        }

        $comptes = $query->latest()->paginate(50);
        return response()->json($comptes);
    }

    public function show(Compte $compte)
    {
        $this->authorizeCompte(Auth::user(), $compte);
        $compte->load(['ville', 'zone', 'delegue', 'contacts']);
        return response()->json($compte);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'ville_id' => 'required|exists:villes,id',
            'zone_id' => 'required|exists:zones,id',
            'quartier_id' => 'nullable|exists:quartiers,id',
            'delegue_id' => 'required|exists:users,id',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $compte = Compte::create($data);
        return response()->json($compte, 201);
    }

    public function update(Request $request, Compte $compte)
    {
        $this->authorizeCompte(Auth::user(), $compte);

        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:100',
            'ville_id' => 'sometimes|exists:villes,id',
            'zone_id' => 'sometimes|exists:zones,id',
            'quartier_id' => 'nullable|exists:quartiers,id',
            'delegue_id' => 'sometimes|exists:users,id',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $compte->update($data);
        return response()->json($compte);
    }

    public function destroy(Compte $compte)
    {
        $user = Auth::user();
        if ($user->role !== 'admin')
            abort(403);
        $compte->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}
