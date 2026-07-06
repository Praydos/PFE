<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\ActionLine;
use App\Models\Compte;
use App\Models\MpDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = Action::with(['compte', 'contact', 'lines.product', 'lines.bss', 'lines.retour', 'lines.mpDelivery']);

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->scopedQuery();
        if ($request->filled('statut'))
            $query->where('statut', $request->statut);
        if ($request->filled('categorie'))
            $query->where('categorie', $request->categorie);
        return response()->json($query->latest()->paginate(50));
    }

    public function show(Action $action)
    {
        $action->load(['compte', 'contact', 'lines.product', 'lines.bss', 'lines.retour', 'lines.mpDelivery', 'delegue']);
        return response()->json($action);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'date_action' => 'required|date',
            'categorie' => 'required|string|max:100',
            'moyen' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
            'lines' => 'nullable|array',
            'lines.*.action_type' => 'required|string|max:100',
            'lines.*.product_id' => 'nullable|exists:products,id',
            'lines.*.bss_id' => 'nullable|exists:bss,id',
            'lines.*.retour_id' => 'nullable|exists:retours,id',
            'lines.*.mp_delivery_id' => 'nullable|exists:mp_deliveries,id',
            'lines.*.quantite' => 'nullable|integer|min:1',
        ]);

        $action = Action::create([
            'compte_id' => $data['compte_id'],
            'contact_id' => $data['contact_id'] ?? null,
            'annee_scolaire_id' => $data['annee_scolaire_id'],
            'date_action' => $data['date_action'],
            'categorie' => $data['categorie'],
            'moyen' => $data['moyen'] ?? null,
            'observations' => $data['observations'] ?? null,
            'delegue_id' => Auth::id(),
            'statut' => 'planifiee',
        ]);

        foreach (($data['lines'] ?? []) as $line) {
            $action->lines()->create($line);
        }

        return response()->json($action->load('lines'), 201);
    }

    public function update(Request $request, Action $action)
    {
        $data = $request->validate([
            'date_action' => 'sometimes|date',
            'categorie' => 'sometimes|string|max:100',
            'moyen' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
        ]);
        $action->update($data);
        return response()->json($action);
    }

    public function destroy(Action $action)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $action->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function realiser(Request $request, Action $action)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' && $user->id !== $action->delegue_id)
            abort(403);

        $data = $request->validate([
            'rapport' => 'required|string',
            'date_realise' => 'nullable|date',
        ]);

        $action->update([
            'statut' => 'realisee',
            'rapport' => $data['rapport'],
            'date_realise' => $data['date_realise'] ?? now()->toDateString(),
        ]);
        return response()->json($action);
    }

    public function valider(Action $action)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']))
            abort(403);
        $action->update(['statut' => 'validee']);
        return response()->json($action);
    }

    public function devalider(Action $action)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']))
            abort(403);
        $action->update(['statut' => 'planifiee', 'rapport' => null, 'date_realise' => null]);
        return response()->json($action);
    }

    public function annuler(Action $action)
    {
        $action->update(['statut' => 'annulee']);
        return response()->json($action);
    }

    public function reporter(Request $request, Action $action)
    {
        $request->validate(['date_action' => 'required|date']);
        $action->update(['date_action' => $request->date_action, 'statut' => 'reportee']);
        return response()->json($action);
    }

    public function getActionTypesByCategorie(Request $request)
    {
        $request->validate(['categorie' => 'required|string']);
        // Adjust to match your actual enum / config setup
        $types = \App\Models\Action::where('categorie', $request->categorie)
            ->distinct()->pluck('action_type');
        return response()->json($types);
    }

    public function getMoyensByActionType(Request $request)
    {
        $request->validate(['action_type' => 'required|string']);
        $moyens = \App\Models\Action::where('categorie', $request->action_type)
            ->distinct()->pluck('moyen');
        return response()->json($moyens);
    }

    public function mpDeliveriesForCompte(Request $request, Compte $compte)
    {
        $deliveries = MpDelivery::where('compte_id', $compte->id)->with('lines.mpProduct')->get();
        return response()->json($deliveries);
    }
}
