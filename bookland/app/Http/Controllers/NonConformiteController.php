<?php

namespace App\Http\Controllers;

use App\Models\NonConformite;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Reclamation;
use App\Models\Product;
use App\Models\Bss;
use App\Models\Examen;
use App\Models\Formation;
use App\Models\Event;
use App\Models\MpDelivery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NonConformiteController extends Controller
{
    private function generateNumero()
    {
        $last = NonConformite::whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
        $increment = $last ? intval(substr($last->numero, -4)) + 1 : 1;
        return 'NC-' . now()->year . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }

    private function authorizeView(NonConformite $nc)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $nc->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($nc->delegue_id)) return;
        }
        abort(403);
    }

    private function authorizeEdit(NonConformite $nc)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'rbo') {
        $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($nc->delegue_id)) return;
        }
        if ($user->role === 'delegue' && $nc->delegue_id === $user->id ) return;
        abort(403);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = NonConformite::with(['compte', 'contact', 'delegate']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('categorie')) $query->where('categorie', $request->categorie);
        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);

        $nonConformites = $query->orderBy('created_at', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $statuts = ['brouillon', 'en_cours', 'termine', 'annule', 'mise_en_attente'];
        $categories = $this->getCategories();

        return view('non_conformites.index', compact('nonConformites', 'comptes', 'statuts', 'categories'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->get();
        $categories = $this->getCategories();
        $sousCategoriesMap = $this->getSousCategories();

        $linkedItemsOptions = $this->getLinkedItemsOptions();

        return view('non_conformites.create', compact('comptes', 'categories', 'sousCategoriesMap', 'linkedItemsOptions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'categorie' => 'required|string',
            'sous_categorie' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
            'module_type' => 'nullable|in:product,specimen,mp,examen,formation,event',
            'module_id' => 'nullable|integer',
        ]);

        // Validation: evaluation required only for audit category
        if ($validated['categorie'] === 'AUDIT & CONTROLE INTERNE' && empty($validated['evaluation'])) {
            return redirect()->back()->withErrors(['evaluation' => 'L\'évaluation est obligatoire pour la catégorie Audit.'])->withInput();
        }

        $validated['numero'] = $this->generateNumero();
        $validated['delegue_id'] = $user->id;
        $validated['date_nc'] = now()->toDateString();
        $validated['statut'] = 'brouillon';
        $validated['module_type'] = $request->module_type;
        $validated['module_id'] = $request->module_id;

        NonConformite::create($validated);

        return redirect()->route('non-conformites.index')->with('success', 'Non‑conformité créée.');
    }

    public function show(NonConformite $non_conformite)
    {
        $this->authorizeView($non_conformite);
        $non_conformite->load(['compte', 'contact', 'delegate', 'responsableEfficacite', 'reclamation']);
        return view('non_conformites.show', compact('non_conformite'));
    }

    public function edit(NonConformite $non_conformite)
    {
        $this->authorizeEdit($non_conformite);
        $user = Auth::user();
        $comptes = Compte::where('delegue_id', $user->id)->get();
        $categories = $this->getCategories();
        $sousCategoriesMap = $this->getSousCategories();
        $statuts = ['brouillon', 'en_cours', 'termine', 'annule', 'mise_en_attente'];
        $linkedItemsOptions = $this->getLinkedItemsOptions();

        return view('non_conformites.edit', compact('non_conformite', 'comptes', 'categories', 'sousCategoriesMap', 'statuts', 'linkedItemsOptions'));
    }

    public function update(Request $request, NonConformite $non_conformite)
    {
        $this->authorizeEdit($non_conformite);
        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'categorie' => 'required|string',
            'sous_categorie' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'objet' => 'required|string',
            'description' => 'required|string',
            'statut' => 'required|in:brouillon,en_cours,termine,annule,mise_en_attente',
            'date_cloture' => 'nullable|date',
            'mode_controle' => 'nullable|string',
            'description_resultat' => 'nullable|string',
            'action_efficace' => 'nullable|boolean',
            'besoin_action_amelioration' => 'nullable|boolean',
            'responsable_efficacite_id' => 'nullable|exists:users,id',
            'date_efficacite' => 'nullable|date',
        ]);

        if ($validated['categorie'] === 'AUDIT & CONTROLE INTERNE' && empty($validated['evaluation'])) {
            return redirect()->back()->withErrors(['evaluation' => 'L\'évaluation est obligatoire pour la catégorie Audit.'])->withInput();
        }

        if ($validated['statut'] === 'termine' && !$validated['date_cloture']) {
            $validated['date_cloture'] = now()->toDateString();
        }

        $non_conformite->update($validated);

        return redirect()->route('non-conformites.show', $non_conformite)->with('success', 'Non‑conformité mise à jour.');
    }

    public function destroy(NonConformite $non_conformite)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $non_conformite->delete();
        return redirect()->route('non-conformites.index')->with('success', 'Non‑conformité supprimée.');
    }

    // Stage 2: edit efficiency separately
    public function editEfficacite(NonConformite $non_conformite)
    {
        $this->authorizeEdit($non_conformite);
        $users = User::whereIn('role', ['admin', 'rbo'])->get();
        return view('non_conformites.edit_efficacite', compact('non_conformite', 'users'));
    }

    public function updateEfficacite(Request $request, NonConformite $non_conformite)
    {
        $this->authorizeEdit($non_conformite);
        $validated = $request->validate([
            'mode_controle' => 'nullable|string',
            'description_resultat' => 'nullable|string',
            'action_efficace' => 'nullable|boolean',
            'besoin_action_amelioration' => 'nullable|boolean',
            'responsable_efficacite_id' => 'required|exists:users,id',
            'date_efficacite' => 'nullable|date',
            'statut' => 'required|in:brouillon,en_cours,termine,annule,mise_en_attente',
            'date_cloture' => 'nullable|date',
        ]);

        if ($validated['statut'] === 'termine' && !$validated['date_cloture']) {
            $validated['date_cloture'] = now()->toDateString();
        }

        $non_conformite->update($validated);

        return redirect()->route('non-conformites.show', $non_conformite)->with('success', 'Efficacité de la non‑conformité enregistrée.');
    }

    // Helper methods
    private function getCategories()
    {
        return [
            'RECLAMATION CLIENTS',
            'RECLAMATION FOURNISSEURS',
            'RECLAMATION INTERNE',
            'AUDIT & CONTROLE INTERNE'
        ];
    }

    private function getSousCategories()
    {
        return [
            'RECLAMATION CLIENTS' => ['Collaborateur', 'Produit', 'Spécimen', 'Matériel Pédagogique', 'Examen', 'Événement', 'Facturation'],
            'RECLAMATION FOURNISSEURS' => ['Produit/Prestation', 'Bon de commande', 'Règlement'],
            'RECLAMATION INTERNE' => ['Fonctionnement Interne', 'Système Documentaire', 'Système Informatique', 'Matériel et équipements', 'Produit'],
            'AUDIT & CONTROLE INTERNE' => ['Documentation', 'Processus', 'Conformité', 'Non-conformité interne'],
        ];
    }

    public function getLinkedModuleAttribute()
    {
        if (!$this->module_type || !$this->module_id) return null;
        
        $map = [
            'product' => Product::class,
            'specimen' => Bss::class,
            'mp' => MpDelivery::class,
            'examen' => Examen::class,
            'formation' => Formation::class,
            'event' => Event::class,
        ];
        
        $class = $map[$this->module_type] ?? null;
        return $class ? $class::find($this->module_id) : null;
    }

    private function getLinkedItemsOptions()
{
    $user = Auth::user();
    $delegateId = $user->role === 'delegue' ? $user->id : null;
    
    $options = [
        'product' => Product::orderBy('titre')->get(['id', 'titre', 'isbn_13', 'isbn_10'])->map(fn($p) => [
            'id' => $p->id,
            'label' => $p->titre . ' (' . ($p->isbn_13 ?? $p->isbn_10) . ')'
        ]),
        'specimen' => Bss::when($delegateId, fn($q) => $q->where('delegate_id', $delegateId))
            ->with('compte')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'label' => $b->numero . ' - ' . ($b->compte->etablissement ?? 'N/A')
            ]),
        'mp' => MpDelivery::when($delegateId, fn($q) => $q->where('delegate_id', $delegateId))
            ->with('mpProduct')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'label' => $m->numero . ' - ' . ($m->mpProduct->nom ?? 'N/A')
            ]),
        'examen' => Examen::when($delegateId, fn($q) => $q->where('delegate_id', $delegateId))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($e) => [
                'id' => $e->id,
                'label' => 'Examen ' . $e->titre . ' (' . ($e->date_examen?->format('d/m/Y') ?? 'N/A') . ')'
            ]),
        'formation' => Formation::when($delegateId, fn($q) => $q->where('delegue_id', $delegateId))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'label' => 'Formation ' . $f->type . ' - ' . ($f->compte->etablissement ?? 'N/A')
            ]),
        'event' => Event::when($delegateId, fn($q) => $q->where('delegate_id', $delegateId))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($ev) => [
                'id' => $ev->id,
                'label' => 'Événement ' . $ev->type . ' - ' . ($ev->ville->nom ?? 'N/A') . ' (' . $ev->date_event->format('d/m/Y') . ')'
            ]),
    ];
    
    return $options;
}
}