<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamenController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = Examen::with(['compte', 'contact', 'epreuves.product']);

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

    public function show(Examen $examen)
    {
        $examen->load(['compte', 'contact', 'epreuves.product']);
        return response()->json($examen);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'date_examen' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'epreuves' => 'nullable|array',
            'epreuves.*.product_id' => 'required|exists:products,id',
            'epreuves.*.quantite' => 'required|integer|min:1',
        ]);

        $examen = Examen::create(array_merge(
            array_except_keys($data, ['epreuves']),
            ['delegue_id' => Auth::id(), 'statut' => 'planifie']
        ));

        foreach (($data['epreuves'] ?? []) as $e) {
            $examen->epreuves()->create($e);
        }

        return response()->json($examen->load('epreuves.product'), 201);
    }

    public function update(Request $request, Examen $examen)
    {
        $data = $request->validate([
            'date_examen' => 'sometimes|date',
            'lieu' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
        ]);
        $examen->update($data);
        return response()->json($examen);
    }

    public function destroy(Examen $examen)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $examen->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function changeStatus(Request $request, Examen $examen)
    {
        $request->validate(['statut' => 'required|string']);
        $examen->update(['statut' => $request->statut]);
        return response()->json($examen);
    }
}

// tiny helper — avoids having to use array_diff_key everywhere
if (!function_exists('array_except_keys')) {
    function array_except_keys(array $arr, array $keys): array
    {
        return array_diff_key($arr, array_flip($keys));
    }
}
