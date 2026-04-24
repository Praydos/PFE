<?php

namespace App\Http\Controllers;

use App\Models\Bss;
use App\Models\BssLigne;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Consignation;
use App\Models\AnneeScolaire;
use App\Models\User;
use App\Models\Action;
use App\Models\ActionLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BssController extends Controller
{
    private function getCurrentYear()
    {
        return AnneeScolaire::where('is_active', true)->first() ?? AnneeScolaire::latest('date_debut')->first();
    }

    // List BSS (role‑based)
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Bss::with(['compte', 'contact', 'delegate', 'anneeScolaire', 'lignes.product']);

        if ($user->role === 'delegue') {
            $query->where('delegate_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegate_id', $delegateIds);
        }
        // admin sees all

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('delegate_id') && $user->role !== 'delegue') {
            $query->where('delegate_id', $request->delegate_id);
        }
        if ($request->filled('compte_id')) {
            $query->where('compte_id', $request->compte_id);
        }

        $bssList = $query->orderBy('created_at', 'desc')->paginate(15);

        // For filters
        $delegates = $user->role === 'admin' ? User::where('role', 'delegue')->get() : collect();
        $comptes = Compte::orderBy('etablissement')->get();

        return view('bss.index', compact('bssList', 'delegates', 'comptes'));
    }

    // Show creation form
    public function create()
{
    $user = Auth::user();
    if ($user->role !== 'delegue') abort(403);

    $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
    $contacts = Contact::whereHas('comptes', fn($q) => $q->where('delegue_id', $user->id))->get();

    $currentYear = $this->getCurrentYear();
    if (!$currentYear) {
        return redirect()->back()->withErrors(['error' => 'Aucune année scolaire définie.']);
    }
    $consignations = Consignation::where('delegate_id', $user->id)
        ->where('annee_scolaire_id', $currentYear->id)
        ->with('product')
        ->get();

    $lastBss = Bss::whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
    $increment = $lastBss ? intval(substr($lastBss->numero, -4)) + 1 : 1;
    $numero = 'BSS-' . now()->year . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);

    return view('bss.create', compact('comptes', 'contacts', 'consignations', 'numero', 'currentYear'));
}

    // Store new BSS
    public function store(Request $request)
{
    $user = Auth::user();
    if ($user->role !== 'delegue') abort(403);

    $rules = [
        'numero' => 'required|unique:bsses,numero',
        'compte_id' => 'required|exists:comptes,id',
        'contact_id' => 'required|exists:contacts,id',
        'date_livraison_prevue' => 'nullable|date',
        'moyen_contact' => 'nullable|in:telephone,email',
        'recupere_par_type' => 'required|in:contact,transport',
        'controle_document' => 'nullable|in:OK,Absence signature,Absence cachet,Absence Document',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ];

    if ($request->recupere_par_type === 'contact') {
        $rules['recupere_par_nom_contact'] = 'required|string|max:255';
    } else {
        $rules['numero_expedition'] = 'required|string|max:255';
    }

    $validated = $request->validate($rules);

    $currentYear = $this->getCurrentYear();
    if (!$currentYear) {
        return redirect()->back()->withErrors(['error' => 'Année scolaire active non trouvée.']);
    }

    // Check already delivered
    $alreadyDelivered = [];
    foreach ($validated['products'] as $item) {
        $already = BssLigne::whereHas('bss', function ($q) use ($validated, $currentYear) {
            $q->where('compte_id', $validated['compte_id'])
              ->where('annee_scolaire_id', $currentYear->id)
              ->where('statut', '!=', 'refuse');
        })->where('product_id', $item['product_id'])->exists();
        if ($already) {
            $product = Product::find($item['product_id']);
            $alreadyDelivered[] = $product->titre . ' (' . ($product->isbn_13 ?? $product->isbn_10) . ')';
        }
    }
    if (!empty($alreadyDelivered)) {
        return redirect()->back()
            ->withErrors(['products' => 'Ces produits ont déjà été livrés à ce compte cette année : ' . implode(', ', $alreadyDelivered) . '. Veuillez les retirer du BSS.'])
            ->withInput();
    }

    $recupereParNom = $validated['recupere_par_type'] === 'contact'
        ? $validated['recupere_par_nom_contact']
        : $validated['numero_expedition'];

    $bss = Bss::create([
        'numero' => $validated['numero'],
        'compte_id' => $validated['compte_id'],
        'contact_id' => $validated['contact_id'],
        'delegate_id' => $user->id,
        'annee_scolaire_id' => $currentYear->id,
        'date_bss' => now()->toDateString(),
        'date_livraison_prevue' => $validated['date_livraison_prevue'] ?? null,
        'moyen_contact' => $validated['moyen_contact'] ?? null,
        'recupere_par_type' => $validated['recupere_par_type'],
        'recupere_par_nom' => $recupereParNom,
        'controle_document' => $validated['controle_document'] ?? null,
        'statut' => 'valide', // No RBO validation needed
    ]);

    foreach ($validated['products'] as $item) {
        $consignation = Consignation::where('delegate_id', $user->id)
            ->where('product_id', $item['product_id'])
            ->where('annee_scolaire_id', $currentYear->id)
            ->first();
        $source = 'magasin';
        if ($consignation && $consignation->quantity >= $item['quantity']) {
            $source = 'consignation';
            $consignation->decrement('quantity', $item['quantity']);
        }

        BssLigne::create([
            'bss_id' => $bss->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'source' => $source,
            'statut_ligne' => 'en_attente',
        ]);
    }

    $compte = Compte::with(['zone', 'ville'])->find($bss->compte_id);
    $lieu = 'Zone: ' . ($compte->zone->name ?? 'N/A') . ' - Ville: ' . ($compte->ville->nom ?? 'N/A');


    // Automatically create an Action for this BSS
    $action = Action::create([
        'objet' => 'Livraison BSS ' . $bss->numero,
        'compte_id' => $bss->compte_id,
        'delegue_id' => $user->id,
        'date_planification' => $bss->date_livraison_prevue ?? now(),
        'statut' => 'planifie',
        'type' => 'commercial',
        'module_lie' => 'bss',
        'module_id' => $bss->id,
        'lieu' => $lieu,
    ]);

    // Create an action line
    $actionLine = ActionLine::create([
        'action_id' => $action->id,
        'categorie' => 'Correspondance',
        'action_type' => 'Livraison Spécimens',
        'moyen' => 'Visite',
        'description' => 'BSS ' . $bss->numero,
    ]);

    // Attach products from BSS lines to the action line
    foreach ($bss->lignes as $bssLine) {
        $actionLine->products()->attach($bssLine->product_id);
    }

    // Attach the contact
if ($bss->contact_id) {
    $actionLine->contacts()->attach($bss->contact_id);
}

    return redirect()->route('bss.index')->with('success', 'BSS créé et livraison planifiée.');
}

    // Show a single BSS (detail)
    public function show(Bss $bss)
    {
        $this->authorizeView($bss);
        $bss->load('lignes.product', 'compte', 'contact', 'delegate');
        return view('bss.show', compact('bss'));
    }

    // Edit feedback (only after validation)
    public function edit(Bss $bss)
    {
        $user = Auth::user();
        // Only delegate who created it can edit feedback, and only if status is 'valide'
        if ($user->role !== 'delegue' || $bss->delegate_id !== $user->id || $bss->statut !== 'valide') {
            abort(403, 'Vous ne pouvez modifier que les BSS validés que vous avez créés.');
        }
        return view('bss.edit_feedback', compact('bss'));
    }

    // Update feedback and document control
    public function update(Request $request, Bss $bss)
{
    $user = Auth::user();
    if ($user->role !== 'delegue' || $bss->delegate_id !== $user->id || $bss->statut !== 'valide') {
        abort(403);
    }
    $validated = $request->validate([
        'feedback' => 'nullable|string',
        'controle_document' => 'nullable|in:OK,Absence signature,Absence cachet,Absence Document',
        'date_feedback' => 'nullable|date',
    ]);
    $bss->update($validated);
    // Update all lines to 'livree'
    foreach ($bss->lignes as $ligne) {
        $ligne->update(['statut_ligne' => 'livree']);
    }
    $bss->update(['statut' => 'livre']);
    return redirect()->route('bss.show', $bss)->with('success', 'Feedback enregistré.');
}

    // Validation by RBO/Admin
    public function validateBss(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        if ($bss->statut !== 'soumis') {
            return redirect()->back()->with('error', 'Ce BSS ne peut pas être validé (statut actuel : ' . $bss->statut . ').');
        }
        $request->validate([
            'action' => 'required|in:approve,refuse',
            'motif_refus' => 'required_if:action,refuse|string|nullable',
        ]);
        if ($request->action === 'approve') {
            $bss->update([
                'statut' => 'valide',
                'is_validated_by_rbo' => true,
                'validated_at' => now(),
                'validated_by' => $user->id,
            ]);
            $message = 'BSS approuvé.';
        } else {
            $bss->update([
                'statut' => 'refuse',
                'motif_refus' => $request->motif_refus,
            ]);
            $message = 'BSS refusé.';
        }
        return redirect()->route('bss.index')->with('success', $message);
    }

    // Admin only: delete BSS (only if status != 'livre' and not validated? optional)
    public function destroy(Bss $bss)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        // Optionally restore consignation stock if source was consignation and not yet delivered
        foreach ($bss->lignes as $ligne) {
            if ($ligne->source === 'consignation' && $ligne->statut_ligne !== 'livree') {
                Consignation::where('delegate_id', $bss->delegate_id)
                    ->where('product_id', $ligne->product_id)
                    ->where('annee_scolaire_id', $bss->annee_scolaire_id)
                    ->increment('quantity', $ligne->quantity);
            }
        }
        $bss->delete();
        return redirect()->route('bss.index')->with('success', 'BSS supprimé.');
    }

    private function authorizeView(Bss $bss)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $bss->delegate_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($bss->delegate_id)) return;
        }
        abort(403);
    }
}