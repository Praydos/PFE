<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BssController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Bss::with(['compte', 'contact', 'lignes.product']);

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = \App\Models\Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->latest()->paginate(50));
    }

    public function show(Bss $bss)
    {
        $bss->load(['compte', 'contact', 'lignes.product']);
        return response()->json($bss);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'delegue']))
            abort(403);

        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'date_envoi' => 'required|date',
            'observations' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.product_id' => 'required|exists:products,id',
            'lignes.*.quantite' => 'required|integer|min:1',
        ]);

        $bss = Bss::create([
            'compte_id' => $data['compte_id'],
            'contact_id' => $data['contact_id'],
            'date_envoi' => $data['date_envoi'],
            'observations' => $data['observations'] ?? null,
            'statut' => 'en_cours',
            'delegue_id' => $user->id,
            'numero' => $this->generateNumero(),
        ]);

        foreach ($data['lignes'] as $ligne) {
            $bss->lignes()->create([
                'product_id' => $ligne['product_id'],
                'quantite' => $ligne['quantite'],
            ]);
        }

        return response()->json($bss->load('lignes.product'), 201);
    }

    public function update(Request $request, Bss $bss)
    {
        $data = $request->validate([
            'statut' => 'sometimes|string',
            'observations' => 'nullable|string',
            'feedback' => 'nullable|string',
        ]);
        $bss->update($data);
        return response()->json($bss);
    }

    public function destroy(Bss $bss)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $bss->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    private function generateNumero(): string
    {
        $year = now()->year;
        $count = Bss::whereYear('created_at', $year)->count() + 1;
        return sprintf('BSS-%d-%04d', $year, $count);
    }
}
