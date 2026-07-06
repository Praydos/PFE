<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = Formation::with(['compte', 'contact', 'ville', 'zone']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $query->whereIn('delegue_id', $delegueIds);
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

    public function show(Formation $formation)
    {
        $formation->load(['compte', 'contact', 'ville', 'zone', 'delegue']);
        return response()->json($formation);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'ville_id' => 'required|exists:villes,id',
            'zone_id' => 'required|exists:zones,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'date_formation' => 'required|date',
            'theme' => 'required|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
        ]);

        $formation = Formation::create($data + [
            'delegue_id' => Auth::id(),
            'statut' => 'planifiee',
        ]);

        return response()->json($formation, 201);
    }

    public function update(Request $request, Formation $formation)
    {
        $data = $request->validate([
            'date_formation' => 'sometimes|date',
            'theme' => 'sometimes|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
        ]);
        $formation->update($data);
        return response()->json($formation);
    }

    public function destroy(Formation $formation)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $formation->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function changeStatus(Request $request, Formation $formation)
    {
        $request->validate(['statut' => 'required|string']);
        $formation->update(['statut' => $request->statut]);
        return response()->json($formation);
    }

    public function realiser(Request $request, Formation $formation)
    {
        $data = $request->validate([
            'rapport_titre' => 'required|string|max:255',
            'rapport_description' => 'required|string',
        ]);
        $formation->update([
            'statut' => 'validee',
            'rapport_titre' => $data['rapport_titre'],
            'rapport_description' => $data['rapport_description'],
        ]);
        return response()->json($formation);
    }

    public function devalider(Formation $formation)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']))
            abort(403);
        $formation->update(['statut' => 'planifiee']);
        return response()->json($formation);
    }
}
