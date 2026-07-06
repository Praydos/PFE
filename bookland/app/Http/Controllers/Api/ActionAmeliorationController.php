<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActionAmelioration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionAmeliorationController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = ActionAmelioration::with(['compte', 'contact', 'responsable']);

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
        return response()->json($this->scopedQuery()->latest()->paginate(50));
    }

    public function show(ActionAmelioration $actions_amelioration)
    {
        $actions_amelioration->load(['compte', 'contact', 'responsable']);
        return response()->json($actions_amelioration);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'cause' => 'nullable|string',
            'date_ouverture' => 'required|date',
            'date_echeance' => 'required|date',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        $action = ActionAmelioration::create($data + [
            'numero' => $this->generateNumero(),
            'statut' => 'ouvert',
        ]);
        return response()->json($action, 201);
    }

    public function update(Request $request, ActionAmelioration $actions_amelioration)
    {
        $data = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'cause' => 'nullable|string',
            'date_echeance' => 'sometimes|date',
            'responsable_id' => 'nullable|exists:users,id',
        ]);
        $actions_amelioration->update($data);
        return response()->json($actions_amelioration);
    }

    public function destroy(ActionAmelioration $actions_amelioration)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $actions_amelioration->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function updateSuivi(Request $request, ActionAmelioration $actions_amelioration)
    {
        $data = $request->validate([
            'suivi' => 'required|string',
            'date_suivi' => 'required|date',
            'statut' => 'nullable|string|max:50',
        ]);
        $actions_amelioration->update($data);
        return response()->json($actions_amelioration);
    }

    public function updateEfficacite(Request $request, ActionAmelioration $actions_amelioration)
    {
        $data = $request->validate([
            'efficacite' => 'required|string',
            'date_efficacite' => 'required|date',
            'statut' => 'nullable|string|max:50',
        ]);
        $actions_amelioration->update($data);
        return response()->json($actions_amelioration);
    }

    private function generateNumero(): string
    {
        $year = now()->year;
        $count = ActionAmelioration::whereYear('created_at', $year)->count() + 1;
        return sprintf('AA-%d-%04d', $year, $count);
    }
}
