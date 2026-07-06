<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Retour;
use App\Models\Bss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetourController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Retour::with(['bss.compte']);

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereHas('bss', fn($q) => $q->whereIn('compte_id', $compteIds));
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = \App\Models\Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereHas('bss', fn($q) => $q->whereIn('compte_id', $compteIds));
        }

        return response()->json($query->latest()->paginate(50));
    }

    public function store(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'delegue']))
            abort(403);

        $data = $request->validate([
            'date_retour' => 'required|date',
            'observations' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.product_id' => 'required|exists:products,id',
            'lignes.*.quantite' => 'required|integer|min:1',
        ]);

        $retour = \App\Models\Retour::create([
            'bss_id' => $bss->id,
            'date_retour' => $data['date_retour'],
            'observations' => $data['observations'] ?? null,
            'numero' => $this->generateNumero(),
        ]);

        foreach ($data['lignes'] as $ligne) {
            $retour->lignes()->create([
                'product_id' => $ligne['product_id'],
                'quantite' => $ligne['quantite'],
            ]);
        }

        return response()->json($retour->load('lignes.product'), 201);
    }

    private function generateNumero(): string
    {
        $year = now()->year;
        $count = \App\Models\Retour::whereYear('created_at', $year)->count() + 1;
        return sprintf('RET-%d-%04d', $year, $count);
    }
}
