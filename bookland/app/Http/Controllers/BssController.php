<?php

namespace App\Http\Controllers;

use App\Models\Bss;
use App\Models\BssLigne;
use App\Models\Consignation;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Product;
use App\Models\AnneeScolaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BssController extends Controller
{
    // Helper: get current active year (or fail)
    private function getCurrentYear()
    {
        $year = AnneeScolaire::where('is_active', true)->first();
        if (!$year) {
            abort(403, 'Aucune année scolaire active. Veuillez en créer une.');
        }
        return $year;
    }

    // Helper: generate unique BSS number (e.g., BSS-2025-0001)
    private function generateNumero($anneeScolaire)
    {
        $yearSuffix = $anneeScolaire->date_debut->format('Y');
        $last = Bss::where('annee_scolaire_id', $anneeScolaire->id)
                    ->orderBy('id', 'desc')
                    ->first();
        $nextId = $last ? intval(substr($last->numero, -4)) + 1 : 1;
        return sprintf('BSS-%s-%04d', $yearSuffix, $nextId);
    }

    // Helper: get comptes accessible to current user (used in create)
    private function getAccessibleComptes()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return Compte::with('ville')->orderBy('etablissement')->get();
        }
        if ($user->role === 'delegue') {
            return Compte::where('delegue_id', $user->id)->with('ville')->orderBy('etablissement')->get();
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            return Compte::whereIn('delegue_id', $delegateIds)->with('ville')->orderBy('etablissement')->get();
        }
        return collect();
    }

    // Helper: get contacts accessible for a given compte (based on user role)
    private function getAccessibleContacts($compteId)
    {
        $compte = Compte::findOrFail($compteId);
        $user = Auth::user();
        if ($user->role === 'admin') {
            return $compte->contacts;
        }
        if ($user->role === 'delegue' && $compte->delegue_id == $user->id) {
            return $compte->contacts;
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($compte->delegue_id)) {
                return $compte->contacts;
            }
        }
        return collect();
    }

    // INDEX - list BSS based on role
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Bss::with(['compte', 'delegate', 'anneeScolaire']);

        // Role-based filtering
        if ($user->role === 'delegue') {
            $query->where('delegate_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegate_id', $delegateIds);
        }
        // Admin sees all

        // Filters
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('delegate_id') && in_array($user->role, ['admin', 'rbo'])) {
            $query->where('delegate_id', $request->delegate_id);
        }
        if ($request->filled('annee_scolaire_id')) {
            $query->where('annee_scolaire_id', $request->annee_scolaire_id);
        } else {
            $query->where('annee_scolaire_id', $this->getCurrentYear()->id);
        }

        $bssList = $query->orderBy('created_at', 'desc')->paginate(20);

        // Data for filters
        $delegates = User::where('role', 'delegue')->orderBy('nom')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $statuses = ['brouillon', 'en_attente', 'valide', 'livre', 'partiel', 'retourne'];

        return view('bss.index', compact('bssList', 'delegates', 'years', 'statuses'));
    }

    // CREATE - show form (only accessible to delegates, RBO, admin)
    public function create(Request $request)
    {
        $user = Auth::user();
        $currentYear = $this->getCurrentYear();

        // Get comptes accessible to user
        $comptes = $this->getAccessibleComptes();

        // Pre-select product if passed from consignation (optional)
        $preSelectedProduct = null;
        if ($request->filled('product_id')) {
            $preSelectedProduct = Product::find($request->product_id);
        }

        // For admin/RBO: they need to select a delegate first (to load his consignation)
        $delegates = null;
        $delegateId = null;
        if ($user->role === 'delegue') {
            $delegateId = $user->id;
        } elseif (in_array($user->role, ['admin', 'rbo'])) {
            $delegates = User::where('role', 'delegue')->orderBy('nom')->get();
            if ($request->filled('delegate_id')) {
                $delegateId = $request->delegate_id;
            }
        }

        // Consignation stock for the selected delegate (if any)
        $stock = [];
        if ($delegateId) {
            $consignations = Consignation::with('product')
                ->where('delegate_id', $delegateId)
                ->where('annee_scolaire_id', $currentYear->id)
                ->get();
            foreach ($consignations as $c) {
                $stock[$c->product_id] = $c->quantity;
            }
        }

        // All products (for product selection)
        $products = Product::orderBy('titre')->get();

        return view('bss.create', compact('comptes', 'currentYear', 'preSelectedProduct', 'stock', 'delegates', 'delegateId', 'products'));
    }

    // STORE - create new BSS
    public function store(Request $request)
    {
        $user = Auth::user();
        $currentYear = $this->getCurrentYear();

        // Determine delegate_id based on role
        if ($user->role === 'delegue') {
            $delegateId = $user->id;
        } else {
            $delegateId = $request->delegate_id;
            if (!$delegateId) {
                return back()->withErrors(['delegate_id' => 'Veuillez sélectionner un délégué.']);
            }
        }

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'moyen_contact' => 'nullable|in:telephone,email',
            'source' => 'required|in:consignation,magasin,transport',
            'date_bss' => 'required|date',
            'date_livraison' => 'nullable|date|after_or_equal:date_bss',
            'recupere_par_type' => 'nullable|in:contact,transport',
            'recupere_par_contact_id' => 'nullable|required_if:recupere_par_type,contact|exists:contacts,id',
            'numero_expedition' => 'nullable|string|max:255',
            'observation' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantite' => 'required|integer|min:1',
        ]);

        // Verify delegate can act on this compte
        $compte = Compte::find($validated['compte_id']);
        if ($compte->delegue_id != $delegateId && $user->role !== 'admin') {
            return back()->withErrors(['compte_id' => 'Ce compte n\'est pas assigné à ce délégué.']);
        }

        // Verify contact belongs to compte
        $contact = Contact::find($validated['contact_id']);
        if (!$contact->comptes->contains($compte->id)) {
            return back()->withErrors(['contact_id' => 'Ce contact n\'appartient pas à ce compte.']);
        }

        // Check for duplicate products in same year for this compte
        $duplicates = [];
        foreach ($validated['products'] as $item) {
            $alreadyGiven = BssLigne::whereHas('bss', function ($q) use ($compte, $currentYear) {
                $q->where('compte_id', $compte->id)
                  ->where('annee_scolaire_id', $currentYear->id)
                  ->whereIn('statut', ['valide', 'livre', 'partiel']);
            })->where('product_id', $item['product_id'])->exists();

            if ($alreadyGiven) {
                $duplicates[] = Product::find($item['product_id'])->titre;
            }
        }

        $needsValidation = !empty($duplicates);

        // Create BSS header
        $bss = Bss::create([
            'numero' => $this->generateNumero($currentYear),
            'compte_id' => $validated['compte_id'],
            'contact_id' => $validated['contact_id'],
            'moyen_contact' => $validated['moyen_contact'] ?? null,
            'delegate_id' => $delegateId,
            'annee_scolaire_id' => $currentYear->id,
            'source' => $validated['source'],
            'date_bss' => $validated['date_bss'],
            'date_livraison' => $validated['date_livraison'] ?? null,
            'recupere_par_type' => $validated['recupere_par_type'] ?? null,
            'recupere_par_contact_id' => $validated['recupere_par_contact_id'] ?? null,
            'numero_expedition' => $validated['numero_expedition'] ?? null,
            'statut' => $needsValidation ? 'en_attente' : 'valide',
            'observation' => $validated['observation'] ?? null,
            'is_active' => true,
        ]);

        if ($needsValidation) {
            $bss->motif_validation = 'Doublon année : ' . implode(', ', $duplicates);
            $bss->save();
        }

        // Create lines and deduct stock only if immediate validation (no duplicates)
        foreach ($validated['products'] as $item) {
            $line = $bss->lignes()->create([
                'product_id' => $item['product_id'],
                'quantite' => $item['quantite'],
                'statut_ligne' => $needsValidation ? 'en_attente' : 'livree',
            ]);

            if (!$needsValidation && $validated['source'] === 'consignation') {
                // Decrease consignation stock
                $cons = Consignation::where('delegate_id', $delegateId)
                    ->where('product_id', $item['product_id'])
                    ->where('annee_scolaire_id', $currentYear->id)
                    ->first();
                if ($cons) {
                    $cons->decrement('quantity', $item['quantite']);
                }
            }
        }

        $message = $needsValidation
            ? 'BSS créé en attente de validation (doublon détecté).'
            : 'BSS créé et validé automatiquement. Stock déduit.';
        return redirect()->route('bss.index')->with('success', $message);
    }

    // SHOW - view details (accessible to all who have permission)
    public function show(Bss $bss)
    {
        $user = Auth::user();
        // Permission check: delegate only his own, RBO only supervised delegates, admin all
        if ($user->role === 'delegue' && $bss->delegate_id != $user->id) {
            abort(403);
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if (!$delegateIds->contains($bss->delegate_id)) {
                abort(403);
            }
        }
        $bss->load(['lignes.product', 'compte', 'contact', 'delegate', 'anneeScolaire']);
        return view('bss.show', compact('bss'));
    }

    // EDIT - only for RBO/admin and only if status is brouillon or en_attente
    public function edit(Bss $bss)
    {
        $user = Auth::user();
        // Only RBO or admin can edit, and only if status allows
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if (!$delegateIds->contains($bss->delegate_id)) {
                abort(403);
            }
        }
        if (!in_array($bss->statut, ['brouillon', 'en_attente'])) {
            return redirect()->route('bss.index')->with('error', 'Impossible de modifier un BSS déjà validé ou livré.');
        }

        $comptes = Compte::orderBy('etablissement')->get();
        $contacts = $bss->compte->contacts; // only contacts of the current compte
        $products = Product::orderBy('titre')->get();
        $currentYear = $this->getCurrentYear();

        return view('bss.edit', compact('bss', 'comptes', 'contacts', 'products', 'currentYear'));
    }

    // UPDATE - for RBO/admin only
    public function update(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if (!$delegateIds->contains($bss->delegate_id)) {
                abort(403);
            }
        }
        if (!in_array($bss->statut, ['brouillon', 'en_attente'])) {
            return back()->with('error', 'Modification interdite.');
        }

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'moyen_contact' => 'nullable|in:telephone,email',
            'source' => 'required|in:consignation,magasin,transport',
            'date_bss' => 'required|date',
            'date_livraison' => 'nullable|date|after_or_equal:date_bss',
            'recupere_par_type' => 'nullable|in:contact,transport',
            'recupere_par_contact_id' => 'nullable|required_if:recupere_par_type,contact|exists:contacts,id',
            'numero_expedition' => 'nullable|string|max:255',
            'observation' => 'nullable|string',
        ]);

        $bss->update($validated);
        return redirect()->route('bss.show', $bss)->with('success', 'BSS mis à jour.');
    }

    // DESTROY - only admin, only if brouillon
    public function destroy(Bss $bss)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        if ($bss->statut !== 'brouillon') {
            return back()->with('error', 'Impossible de supprimer un BSS non brouillon.');
        }
        $bss->delete();
        return redirect()->route('bss.index')->with('success', 'BSS supprimé.');
    }

    // VALIDATE - RBO or admin (only for BSS in 'en_attente' status)
    public function validateBss(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if (!$delegateIds->contains($bss->delegate_id)) {
                abort(403);
            }
        }
        if ($bss->statut !== 'en_attente') {
            return back()->with('error', 'Ce BSS n\'est pas en attente de validation.');
        }

        DB::transaction(function () use ($bss, $user) {
            $bss->statut = 'valide';
            $bss->validated_by = $user->id;
            $bss->save();

            // Deduct stock if source is consignation
            if ($bss->source === 'consignation') {
                foreach ($bss->lignes as $line) {
                    $cons = Consignation::where('delegate_id', $bss->delegate_id)
                        ->where('product_id', $line->product_id)
                        ->where('annee_scolaire_id', $bss->annee_scolaire_id)
                        ->first();
                    if ($cons) {
                        $cons->decrement('quantity', $line->quantite);
                    }
                }
            }

            // Update line status
            foreach ($bss->lignes as $line) {
                $line->statut_ligne = 'livree';
                $line->save();
            }
        });

        return redirect()->route('bss.show', $bss)->with('success', 'BSS validé et stock déduit.');
    }

    // MARK AS DELIVERED (physical delivery) - anyone who can edit
    public function markDelivered(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']) && ($user->role === 'delegue' && $bss->delegate_id != $user->id)) {
            abort(403);
        }
        if ($bss->statut !== 'valide') {
            return back()->with('error', 'Seul un BSS validé peut être marqué livré.');
        }
        $bss->statut = 'livre';
        $bss->date_livraison = $request->date_livraison ?? now();
        $bss->save();
        return redirect()->back()->with('success', 'BSS marqué comme livré.');
    }

    // Physical control (signature/stamp)
    public function updateControl(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        $request->validate(['controle' => 'required|in:OK,absence_signature,absence_cachet,absence_document']);
        $bss->controle = $request->controle;
        $bss->save();
        return redirect()->back()->with('success', 'Contrôle enregistré.');
    }

    // School feedback
    public function updateFeedback(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        $request->validate([
            'feedback_statut' => 'required|in:confirme,defavorable',
            'feedback_date' => 'nullable|date',
            'feedback_contact_id' => 'nullable|exists:contacts,id',
            'feedback_moyen' => 'nullable|in:email,telephone,sms,courrier',
        ]);
        $bss->update($request->only(['feedback_statut', 'feedback_date', 'feedback_contact_id', 'feedback_moyen']));
        return redirect()->back()->with('success', 'Feedback enregistré.');
    }
}