<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemandeSpecimen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeSpecimenController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = DemandeSpecimen::with(['compte', 'contact', 'lignes.product']);

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = \App\Models\Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->scopedQuery();
        if ($request->filled('statut'))
            $query->where('statut', $request->statut);
        return response()->json($query->latest()->paginate(50));
    }

    public function show(DemandeSpecimen $demandes_specimen)
    {
        $demandes_specimen->load(['compte', 'contact', 'lignes.product']);
        return response()->json($demandes_specimen);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'date_demande' => 'required|date',
            'observations' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.product_id' => 'required|exists:products,id',
            'lignes.*.quantite' => 'required|integer|min:1',
        ]);

        $demande = DemandeSpecimen::create([
            'compte_id' => $data['compte_id'],
            'contact_id' => $data['contact_id'] ?? null,
            'date_demande' => $data['date_demande'],
            'observations' => $data['observations'] ?? null,
            'delegue_id' => Auth::id(),
            'statut' => 'en_attente',
            'numero' => $this->generateNumero(),
        ]);

        foreach ($data['lignes'] as $ligne) {
            $demande->lignes()->create([
                'product_id' => $ligne['product_id'],
                'quantite' => $ligne['quantite'],
            ]);
        }

        return response()->json($demande->load('lignes.product'), 201);
    }

    public function update(Request $request, DemandeSpecimen $demandes_specimen)
    {
        $data = $request->validate([
            'observations' => 'nullable|string',
            'statut' => 'sometimes|string|max:50',
        ]);
        $demandes_specimen->update($data);
        return response()->json($demandes_specimen);
    }

    public function destroy(DemandeSpecimen $demandes_specimen)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $demandes_specimen->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function validateRequest(Request $request, DemandeSpecimen $demandes_specimen)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']))
            abort(403);
        $demandes_specimen->update(['statut' => 'validee']);
        return response()->json($demandes_specimen);
    }

    private function generateNumero(): string
    {
        $year = now()->year;
        $count = DemandeSpecimen::whereYear('created_at', $year)->count() + 1;
        return sprintf('DS-%d-%04d', $year, $count);
    }
}
