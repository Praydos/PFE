<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Adoption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdoptionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Adoption::with(['compte', 'contact', 'product', 'anneeScolaire']);

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = \App\Models\Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        }

        return response()->json($query->latest()->paginate(50));
    }

    public function show(Adoption $adoption)
    {
        $adoption->load(['compte', 'contact', 'product', 'anneeScolaire']);
        return response()->json($adoption);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'delegue']))
            abort(403);

        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'product_id' => 'required|exists:products,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'niveau' => 'required|string|max:100',
            'quantite' => 'required|integer|min:1',
            'observations' => 'nullable|string',
        ]);

        $adoption = Adoption::create($data + ['delegue_id' => $user->id]);
        return response()->json($adoption->load(['compte', 'product']), 201);
    }
}
