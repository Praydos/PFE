<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NonConformite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NonConformiteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = NonConformite::with(['compte', 'contact']);

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = \App\Models\Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        }

        if ($request->filled('statut'))
            $query->where('statut', $request->statut);
        return response()->json($query->latest()->paginate(50));
    }

    public function show(NonConformite $non_conformite)
    {
        $non_conformite->load(['compte', 'contact']);
        return response()->json($non_conformite);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'description' => 'required|string',
            'date_constat' => 'required|date',
            'type' => 'nullable|string|max:100',
        ]);

        $nc = NonConformite::create($data + [
            'delegue_id' => Auth::id(),
            'statut' => 'ouverte',
            'numero' => $this->generateNumero(),
        ]);
        return response()->json($nc, 201);
    }

    public function update(Request $request, NonConformite $non_conformite)
    {
        $data = $request->validate([
            'description' => 'sometimes|string',
            'statut' => 'sometimes|string|max:50',
            'traitement' => 'nullable|string',
            'date_cloture' => 'nullable|date',
        ]);
        $non_conformite->update($data);
        return response()->json($non_conformite);
    }

    public function destroy(NonConformite $non_conformite)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $non_conformite->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function updateEfficacite(Request $request, NonConformite $non_conformite)
    {
        $data = $request->validate([
            'efficacite' => 'required|string',
            'date_efficacite' => 'required|date',
        ]);
        $non_conformite->update($data + ['statut' => 'cloturee']);
        return response()->json($non_conformite);
    }

    private function generateNumero(): string
    {
        $year = now()->year;
        $count = NonConformite::whereYear('created_at', $year)->count() + 1;
        return sprintf('NC-%d-%04d', $year, $count);
    }
}
