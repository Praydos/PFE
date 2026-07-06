<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    private function authorizeView(Reclamation $reclamation)
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if (in_array($user->role, ['rbo', 'abo'])) {
            // RBO can see reclamations from their supervised delegates
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            if ($delegueIds->contains($reclamation->delegue_id))
                return;
            abort(403);
        }
        if ($user->id !== $reclamation->delegue_id)
            abort(403);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Reclamation::with(['compte', 'contact']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $query->whereIn('delegue_id', $delegueIds);
        }

        if ($request->filled('statut'))
            $query->where('statut', $request->statut);
        return response()->json($query->latest()->paginate(50));
    }

    public function show(Reclamation $reclamation)
    {
        $this->authorizeView($reclamation);
        $reclamation->load(['compte', 'contact']);
        return response()->json($reclamation);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'categorie' => 'required|string|max:100',
            'sous_categorie' => 'nullable|string|max:100',
            'description' => 'required|string',
            'date_reclamation' => 'required|date',
        ]);

        $reclamation = Reclamation::create($data + [
            'delegue_id' => Auth::id(),
            'statut' => 'ouverte',
            'reference' => $this->generateReference(),
        ]);
        return response()->json($reclamation, 201);
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        $this->authorizeView($reclamation);
        $data = $request->validate([
            'categorie' => 'sometimes|string|max:100',
            'sous_categorie' => 'nullable|string|max:100',
            'description' => 'sometimes|string',
            'statut' => 'sometimes|string|max:50',
            'traitement' => 'nullable|string',
        ]);
        $reclamation->update($data);
        return response()->json($reclamation);
    }

    public function destroy(Reclamation $reclamation)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $reclamation->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    private function generateReference(): string
    {
        $year = now()->year;
        $count = Reclamation::whereYear('created_at', $year)->count() + 1;
        return sprintf('REC-%d-%04d', $year, $count);
    }
}
