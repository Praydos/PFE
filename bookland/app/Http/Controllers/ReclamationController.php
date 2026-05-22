<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Examen;
use App\Models\Event;
use App\Models\User;
use App\Models\Product;
use App\Models\Bss;
use App\Models\MpProduct;
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
        if ($user->role !== 'admin' && ($user->role !== 'delegue')) abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->get();
        $produits = Product::orderBy('titre')->get();
        $specimens = Bss::where('delegue_id', $user->id)->whereIn('statut', ['valide', 'livre'])->with('compte')->get();
        $mps = MpProduct::orderBy('nom')->get();
        $types = ['face_a_face', 'email', 'telephone', 'fax'];
        $categories = $this->getCategories();
        $sousCategoriesMap = $this->getSousCategories();
        $statuts = ['brouillon', 'en_cours', 'mise_en_attente', 'cloturee', 'annulee'];
        $examens = Examen::orderBy('date_demande', 'desc')->get();
        $events  = Event::orderBy('date_event', 'desc')->get();
        

        return view('reclamations.create', compact('comptes', 'produits', 'specimens', 'mps', 'types', 'categories', 
        'sousCategoriesMap', 'statuts', 'examens', 'events'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && ($user->role !== 'delegue')) abort(403);

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'date_reclamation' => 'required|date',
            'type' => 'nullable|in:face_a_face,email,telephone,fax',
            'categorie' => 'required|string',
            'sous_categorie' => 'nullable|string',
            'description' => 'required|string|min:10',
            'produit_id' => 'nullable|exists:products,id',
            'specimen_id' => 'nullable|exists:bsses,id',
            'mp_id' => 'nullable|exists:mp_products,id',
            'module_lie' => 'nullable|in:product,specimen,mp,examen,event',
            'module_id'  => 'nullable|integer',
            'est_non_conformite' => 'nullable|boolean',
            'besoin_action_amelioration' => 'nullable|boolean',
        ]);

        $validated['reference'] = $this->generateReference();
        $validated['delegue_id'] = $user->id;
        $validated['statut'] = 'brouillon';
        $validated['created_by'] = $user->id;


        if ($validated['module_lie'] && $validated['module_id']) {
    $model = match($validated['module_lie']) {
        'product'  => Product::class,
        'specimen' => Bss::class,
        'mp'       => MpProduct::class,
        'examen'   => Examen::class,
        'event'    => Event::class,
        default    => null,
    };
    if ($model && !$model::where('id', $validated['module_id'])->exists()) {
        return back()->withErrors(['module_id' => 'Élément lié introuvable.']);
    }
}

        Reclamation::create($validated);

        return redirect()->route('reclamations.index')->with('success', 'Réclamation enregistrée.');
    }

    public function show(Reclamation $reclamation)
{
    $this->authorizeView($reclamation);
    $reclamation->load(['compte', 'contact', 'delegate', 'responsable', 'createdBy', 'updatedBy']);

    $linkedModule = null;
    if ($reclamation->module_lie && $reclamation->module_id) {
        $modelMap = [
            'examen'   => Examen::class,
            'event'    => Event::class,
            'product'  => Product::class,
            'specimen' => Bss::class,
            'mp'       => MpProduct::class,
        ];
        $class = $modelMap[$reclamation->module_lie] ?? null;
        if ($class) {
            $linkedModule = $class::find($reclamation->module_id);
        }
    }

    return view('reclamations.show', compact('reclamation', 'linkedModule'));
}

    public function edit(Reclamation $reclamation)
    {
        $this->authorizeEdit($reclamation);
        $user = Auth::user();
        $comptes = Compte::where('delegue_id', $user->id)->get();
        $produits = Product::orderBy('titre')->get();
        $specimens = Bss::where('delegue_id', $user->id)->whereIn('statut', ['valide', 'livre'])->with('compte')->get();
        $mps = MpProduct::orderBy('nom')->get();
        $types = ['face_a_face', 'email', 'telephone', 'fax'];
        $categories = $this->getCategories();
        $sousCategoriesMap = $this->getSousCategories();
        $statuts = ['brouillon', 'en_cours', 'mise_en_attente', 'cloturee', 'annulee'];
        $examens = Examen::orderBy('date_demande', 'desc')->get();
        $events  = Event::orderBy('date_event', 'desc')->get();
        

        return view('reclamations.edit', compact('reclamation', 'comptes', 'produits', 'specimens', 'mps', 'types',
         'categories', 'sousCategoriesMap', 'statuts', 'examens', 'events'));
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        $this->authorizeEdit($reclamation);
        $user = Auth::user();

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'date_reclamation' => 'required|date',
            'priorite' => 'nullable|in:basse,moyenne,haute',
            'type' => 'nullable|in:face_a_face,email,telephone,fax',
            'categorie' => 'required|string',
            'sous_categorie' => 'nullable|string',
            'description' => 'required|string',
            'analyse' => 'nullable|string',
            'reponse' => 'nullable|string',
            'date_reponse' => 'nullable|date',
            'responsable_id' => 'nullable|exists:contacts,id',
            'statut' => 'required|in:brouillon,en_cours,mise_en_attente,cloturee,annulee',
            'date_cloture' => 'nullable|date',
            'produit_id' => 'nullable|exists:products,id',
            'specimen_id' => 'nullable|exists:bss,id',
            'mp_id' => 'nullable|exists:mp_products,id',
            'est_non_conformite' => 'nullable|boolean',
            'besoin_action_amelioration' => 'nullable|boolean',
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
        return ['Produit', 'Spécimen', 'Matériel pédagogique',  'Événement', 'Autre'];
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