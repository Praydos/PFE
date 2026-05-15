<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    private function generateReference()
    {
        $last = Reclamation::whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
        $increment = $last ? intval(substr($last->reference, -4)) + 1 : 1;
        return 'REC-' . now()->year . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }

    private function authorizeView(Reclamation $reclamation)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $reclamation->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($reclamation->delegue_id)) return;
        }
        abort(403);
    }

    private function authorizeEdit(Reclamation $reclamation)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $reclamation->delegue_id === $user->id && $reclamation->statut === 'brouillon') return;
        abort(403);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Reclamation::with(['compte', 'contact', 'delegate', 'responsable']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('categorie')) $query->where('categorie', $request->categorie);
        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);

        $reclamations = $query->orderBy('created_at', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $statuts = ['brouillon', 'en_cours', 'mise_en_attente', 'cloturee', 'annulee'];
        $categories = $this->getCategories();

        return view('reclamations.index', compact('reclamations', 'comptes', 'statuts', 'categories'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->get();
        $types = ['face_a_face', 'email', 'telephone', 'fax'];
        $categories = $this->getCategories();
        $sousCategories = $this->getSousCategories();
        $modules = ['product', 'specimen', 'mp', 'examen', 'event', 'facturation'];

        return view('reclamations.create', compact('comptes', 'types', 'categories', 'sousCategories', 'modules'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'date_reclamation' => 'required|date',
            'type' => 'nullable|in:face_a_face,email,telephone,fax',
            'categorie' => 'required|string',
            'sous_categorie' => 'nullable|string',
            'description' => 'required|string|min:10',
            'module_lie' => 'nullable|string',
            'module_id' => 'nullable|integer',
        ]);

        $validated['reference'] = $this->generateReference();
        $validated['delegue_id'] = $user->id;
        $validated['statut'] = 'brouillon';
        $validated['created_by'] = $user->id;

        Reclamation::create($validated);

        return redirect()->route('reclamations.index')->with('success', 'Réclamation enregistrée.');
    }

    public function show(Reclamation $reclamation)
    {
        $this->authorizeView($reclamation);
        $reclamation->load(['compte', 'contact', 'delegate', 'responsable', 'createdBy', 'updatedBy']);
        return view('reclamations.show', compact('reclamation'));
    }

    public function edit(Reclamation $reclamation)
    {
        $this->authorizeEdit($reclamation);
        $user = Auth::user();
        $comptes = Compte::where('delegue_id', $user->id)->get();
        $types = ['face_a_face', 'email', 'telephone', 'fax'];
        $categories = $this->getCategories();
        $sousCategories = $this->getSousCategories();
        $modules = ['product', 'specimen', 'mp', 'examen', 'event', 'facturation'];
        $statuts = ['brouillon', 'en_cours', 'mise_en_attente', 'cloturee', 'annulee'];

        return view('reclamations.edit', compact('reclamation', 'comptes', 'types', 'categories', 'sousCategories', 'modules', 'statuts'));
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        $this->authorizeEdit($reclamation);
        $user = Auth::user();

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'date_reclamation' => 'required|date',
            'date_echeance' => 'nullable|date',
            'priorite' => 'nullable|in:basse,moyenne,haute',
            'type' => 'nullable|in:face_a_face,email,telephone,fax',
            'categorie' => 'required|string',
            'sous_categorie' => 'nullable|string',
            'description' => 'required|string',
            'analyse' => 'nullable|string',
            'reponse' => 'nullable|string',
            'responsable_id' => 'nullable|exists:users,id',
            'statut' => 'required|in:brouillon,en_cours,mise_en_attente,cloturee,annulee',
            'date_cloture' => 'nullable|date',
        ]);

        if ($validated['statut'] === 'cloturee' && !$validated['date_cloture']) {
            $validated['date_cloture'] = now()->toDateString();
        }

        $validated['updated_by'] = $user->id;
        $reclamation->update($validated);

        return redirect()->route('reclamations.show', $reclamation)->with('success', 'Réclamation mise à jour.');
    }

    public function destroy(Reclamation $reclamation)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $reclamation->delete();
        return redirect()->route('reclamations.index')->with('success', 'Réclamation supprimée.');
    }

    private function getCategories()
    {
        return ['Produit', 'Spécimen', 'Matériel pédagogique', 'Examen', 'Événement', 'Facturation', 'Autre'];
    }

    private function getSousCategories()
    {
        return [
            'Produit' => ['Défaut fabrication', 'Délai livraison', 'Contenu erroné', 'Qualité'],
            'Spécimen' => ['Non reçu', 'Quantité insuffisante', 'Produit erroné'],
            'Matériel pédagogique' => ['Manque pièces', 'Défaut impression'],
            'Examen' => ['Résultats non reçus', 'Problème organisation'],
            'Événement' => ['Annulation', 'Retard', 'Informations inexactes'],
            'Facturation' => ['Erreur montant', 'Doublon', 'Absence facture'],
            'Autre' => ['Autre motif'],
        ];
    }
}