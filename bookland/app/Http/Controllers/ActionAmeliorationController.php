<?php

namespace App\Http\Controllers;

use App\Models\ActionAmelioration;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionAmeliorationController extends Controller
{
    private function generateNumero()
    {
        $last = ActionAmelioration::whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
        $increment = $last ? intval(substr($last->numero, -4)) + 1 : 1;
        return 'AA-' . now()->year . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ActionAmelioration::with(['compte', 'emetteur', 'responsableSuivi', 'responsableEfficacite']);

        if ($user->role === 'delegue') {
            $query->whereHas('compte', fn($q) => $q->where('delegue_id', $user->id));
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereHas('compte', fn($q) => $q->whereIn('delegue_id', $delegateIds));
        }

        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);

        $actions = $query->orderBy('created_at', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $statuts = ['brouillon', 'en_cours', 'termine', 'annule', 'en_attente'];
        $types = ['Action corrective', 'Action préventive', 'Action d\'amélioration'];
        $origines = ['Réclamation client', 'Audit et controle interne', 'PROJET D’AMÉLIORATION', 'Réclamation fournisseur', 'Dysfonctionnement interne'];

        return view('actions_amelioration.index', compact('actions', 'comptes', 'statuts', 'types', 'origines'));
    }

    // Stage 1: create
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' && $user->role !== 'admin') abort(403);
        $comptes = Compte::where('delegue_id', $user->id)->get();
        $types = ['Action corrective', 'Action préventive', 'Action d\'amélioration'];
        $origines = 
        ['Réclamation client', 'Audit et controle interne', 'PROJET D’AMÉLIORATION', "RECLAMATION COLLABORATEUR",
        'Réclamation fournisseur', 'Dysfonctionnement interne',"REVUE DE DIRECTION","RÉUNION PROCESSUS","SÉCURITÉ"];
        return view('actions_amelioration.create', compact('comptes', 'types', 'origines'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' && $user->role !== 'admin') abort(403);

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'emetteur_id' => 'required|exists:contacts,id',
            'dateAA' => 'required|date',
            'type' => 'required|string|in:Action corrective,Action préventive,Action d\'amélioration',
            'origine' => 'required|string',
            'analyse_causes' => 'nullable|string',
            'sanctions' => 'nullable|string',
            'resultats_attendus' => 'nullable|string',
        ]);

        $validated['numero'] = $this->generateNumero();
        $validated['statut'] = 'brouillon';
        $action = ActionAmelioration::create($validated);

        return redirect()->route('actions-amelioration.show', $action)->with('success', 'Action créée. Vous pouvez maintenant ajouter le suivi.');
    }

    public function show(ActionAmelioration $actions_amelioration)
    {
        $this->authorizeView($actions_amelioration);
        return view('actions_amelioration.show', compact('actions_amelioration'));
    }

    // Stage 2: suivi
    public function editSuivi(ActionAmelioration $actions_amelioration)
    {
        $this->authorizeEdit($actions_amelioration);
        $contacts = $actions_amelioration->compte->contacts;
        return view('actions_amelioration.edit_suivi', compact('actions_amelioration', 'contacts'));
    }

    public function updateSuivi(Request $request, ActionAmelioration $actions_amelioration)
    {
        $this->authorizeEdit($actions_amelioration);
        $validated = $request->validate([
            'verification_mise_en_oeuvre' => 'nullable|string',
            'responsable_suivi_id' => 'required|exists:users,id',
            'date_suivi' => 'nullable|date',
        ]);
        $actions_amelioration->update($validated);
        $actions_amelioration->update(['statut' => 'en_cours']);
        return redirect()->route('actions-amelioration.show', $actions_amelioration)->with('success', 'Suivi enregistré.');
    }

    // Stage 3: efficacité
    public function editEfficacite(ActionAmelioration $actions_amelioration)
    {
        $this->authorizeEdit($actions_amelioration);
        $contacts = $actions_amelioration->compte->contacts;
        return view('actions_amelioration.edit_efficacite', compact('actions_amelioration', 'contacts'));
    }

    public function updateEfficacite(Request $request, ActionAmelioration $actions_amelioration)
    {
        $this->authorizeEdit($actions_amelioration);
        $validated = $request->validate([
            'date_efficacite' => 'nullable|date',
            'responsable_efficacite_id' => 'required|exists:users,id',
            'mode_controle' => 'nullable|string',
            'description_resultat' => 'nullable|string',
            'action_efficace' => 'nullable|boolean',
            'besoin_action_amelioration' => 'nullable|boolean',
            'statut' => 'required|in:brouillon,en_cours,termine,annule,en_attente',
            'date_cloture' => 'nullable|date',
        ]);
        $actions_amelioration->update($validated);
        if ($validated['statut'] === 'termine') {
            $actions_amelioration->update(['date_cloture' => now()]);
        }
        return redirect()->route('actions-amelioration.show', $actions_amelioration)->with('success', 'Évaluation enregistrée.');
    }

    // Standard edit (full) – kept for admin but not used in workflow
    public function edit(ActionAmelioration $actions_amelioration)
    {
        $this->authorizeEdit($actions_amelioration);
        $comptes = Compte::orderBy('etablissement')->get();
        $users = User::whereIn('role', ['admin', 'rbo', 'delegue'])->get();
        $types = ['Action corrective', 'Action préventive', 'Action d\'amélioration'];
        $origines = ['Réclamation client', 'Audit et controle interne', 'PROJET D’AMÉLIORATION', 'Réclamation fournisseur', 'Dysfonctionnement interne'];
        $statuts = ['brouillon', 'en_cours', 'termine', 'annule', 'en_attente'];
        return view('actions_amelioration.edit', compact('actions_amelioration', 'comptes', 'users', 'types', 'origines', 'statuts'));
    }

    public function destroy(ActionAmelioration $actions_amelioration)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $actions_amelioration->delete();
        return redirect()->route('actions-amelioration.index')->with('success', 'Action supprimée.');
    }

    private function authorizeView(ActionAmelioration $action)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $action->compte->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($action->compte->delegue_id)) return;
        }
        abort(403);
    }

    private function authorizeEdit(ActionAmelioration $action)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $action->compte->delegue_id === $user->id) return;
        abort(403);
    }
}