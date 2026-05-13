<?php

namespace App\Http\Controllers;

use App\Models\DemandeSpecimen;
use App\Models\DemandeLigne;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\Product;
use App\Models\Bss;
use App\Models\BssLigne;
use App\Models\AnneeScolaire;
use App\Models\Consignation;
use App\Support\YearLock;
use Illuminate\Http\Request;
use App\Models\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DemandeSpecimenController extends Controller
{
    private function getCurrentYear()
    {
        return AnneeScolaire::where('is_active', true)->first() ?? AnneeScolaire::latest('date_debut')->first();
    }

    // List requests (role‑based)
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = DemandeSpecimen::with(['compte', 'contact', 'delegate', 'ville', 'zone', 'originalBss']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);

        $demandes = $query->orderBy('created_at', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $statuts = ['demande', 'valide', 'decline', 'annule'];
        $types = ['etablissement', 'personnelle'];

        return view('demandes_specimens.index', compact('demandes', 'comptes', 'statuts', 'types'));
    }

    // Create form
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' && $user->role !== 'admin') abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $products = Product::orderBy('titre')->get();
        $currentYear = $this->getCurrentYear();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $villes = $this->getUserVilles($user);
        $zones = Zone::all();

        $selectedCompteId = request('compte_id');
        $defaultVilleId = null;
        $defaultZoneId = null;
        $defaultContactId = null;
        if ($selectedCompteId && $comptes->contains('id', $selectedCompteId)) {
            $compte = $comptes->find($selectedCompteId);
            $defaultVilleId = $compte->ville_id;
            $defaultZoneId = $compte->zone_id;
        }

        return view('demandes_specimens.create', compact('comptes', 'products', 'currentYear', 'years', 'villes', 'zones', 'selectedCompteId', 'defaultVilleId', 'defaultZoneId'));
    }

    /**
     * Store new special demande request
     * 
     * NEW LOGIC:
     * 1. Check if product exists in delegate's consignation
     * 2. If product in consignation: check if already delivered to compte
     *    - If delivered: create special demande (waiting for validation)
     *    - If never delivered: block and ask to create normal BSS
     * 3. If product NOT in consignation: create as request (waiting for validation)
     * 4. Status is "demande" (pending)
     * 5. Upon admin/rbo validation: create BSS with "special" in title and change status to "valide"
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' && $user->role !== 'admin') abort(403);

        $validated = $request->validate([
            'type' => 'required|in:etablissement,personnelle',
            'compte_id' => 'required_if:type,etablissement|nullable|exists:comptes,id',
            'contact_id' => 'required_if:type,personnelle|nullable|exists:contacts,id',
            'ville_id' => 'required_if:type,personnelle|nullable|exists:villes,id',
            'zone_id' => 'required_if:type,personnelle|nullable|exists:zones,id',
            'description' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $currentYear = $this->getCurrentYear();
        if (!$currentYear) {
            return redirect()->back()->withErrors(['error' => 'Année scolaire active non trouvée.']);
        }

        $previousYear = AnneeScolaire::where('date_debut', '<', $currentYear->date_debut)
            ->orderBy('date_debut', 'desc')
            ->first();

        $yearIds = [$currentYear->id];
        if ($previousYear) {
            $yearIds[] = $previousYear->id;
        }

        // For type=etablissement, auto-set ville/zone from compte
        if ($validated['type'] === 'etablissement') {
            $compte = Compte::find($validated['compte_id']);
            $villeId = $compte->ville_id;
            $zoneId = $compte->zone_id;
            $compteId = $compte->id;
        } else {
            $villeId = $validated['ville_id'];
            $zoneId = $validated['zone_id'];
            $compteId = $validated['compte_id'];
        }

        // ========================================================================
        // NEW VALIDATION LOGIC: Check products against consignation & deliveries
        // ========================================================================
        
        foreach ($validated['products'] as $item) {
            $productId = $item['product_id'];
            
            // Check if product exists in delegate's consignation
            $consignation = Consignation::where('delegate_id', $user->id)
                ->where('product_id', $productId)
                ->where('annee_scolaire_id', $currentYear->id)
                ->first();

            if ($consignation) {
                // Product is in consignation
                // Now check if already delivered to this compte
                $alreadyDelivered = BssLigne::where('product_id', $productId)
                    ->whereHas('bss', function ($q) use ($compteId, $yearIds) {
                        $q->where('compte_id', $compteId)
                          ->whereIn('annee_scolaire_id', $yearIds)
                          ->whereIn('statut', ['valide', 'livre']);
                    })
                    ->exists();

                if (!$alreadyDelivered) {
                    // Product in consignation but NEVER delivered to this compte
                    return back()->withErrors([
                        'products' => "Le produit '{$consignation->product->titre}' est en consignation mais n'a jamais été livré à ce compte. Veuillez créer un BSS normal en premier."
                    ]);
                }
                // Product in consignation AND already delivered -> OK, allow special demande
            } else {
                // Product NOT in consignation -> OK, create as request (pending validation)
                // No error here, just proceed
            }
        }

        // ========================================================================
        // CREATE SPECIAL DEMANDE IN "DEMANDE" STATUS (PENDING VALIDATION)
        // ========================================================================
        
        $demande = DemandeSpecimen::create([
            'type' => $validated['type'],
            'compte_id' => $compteId ?? null,
            'contact_id' => $validated['contact_id'] ?? null,
            'delegue_id' => $user->id,
            'annee_scolaire_id' => $currentYear->id,
            'ville_id' => $villeId,
            'zone_id' => $zoneId,
            'date_demande' => now()->toDateString(),
            'description' => $validated['description'] ?? null,
            'statut' => 'demande', // Pending admin/rbo validation
            'original_bss_id' => null, // Will be set after validation
        ]);

        foreach ($validated['products'] as $item) {
            DemandeLigne::create([
                'demande_id' => $demande->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('demandes-specimens.index')
            ->with('success', 'Demande spéciale créée. En attente de validation par l\'administrateur ou le RBO.');
    }

    // Show detail
    public function show(DemandeSpecimen $demandes_specimen)
    {
        $this->authorizeView($demandes_specimen);
        $demandes_specimen->load('lignes.product', 'compte', 'contact', 'ville', 'zone', 'originalBss', 'validePar');
        
        // Check if validated (locked)
        $isLocked = $demandes_specimen->statut === 'valide';
        
        return view('demandes_specimens.show', compact('demandes_specimen', 'isLocked'));
    }

    // Edit (only for demande status - pending validation)
    public function edit(DemandeSpecimen $demandes_specimen)
    {
        $this->authorizeEdit($demandes_specimen);
        if ($demandes_specimen->statut !== 'demande') {
            return redirect()->route('demandes-specimens.index')
                ->with('error', 'Seules les demandes en attente peuvent être modifiées.');
        }
        
        $user = Auth::user();
        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $products = Product::orderBy('titre')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $villes = $this->getUserVilles($user);
        $zones = Zone::all();
        
        return view('demandes_specimens.edit', compact('demandes_specimen', 'comptes', 'products', 'years', 'villes', 'zones'));
    }

    // Update (only before validation)
    public function update(Request $request, DemandeSpecimen $demandes_specimen)
    {
        YearLock::check($demandes_specimen);
        $this->authorizeEdit($demandes_specimen);
        if ($demandes_specimen->statut !== 'demande') abort(403);

        $validated = $request->validate([
            'type' => 'required|in:etablissement,personnelle',
            'compte_id' => 'required_if:type,etablissement|nullable|exists:comptes,id',
            'contact_id' => 'required_if:type,personnelle|nullable|exists:contacts,id',
            'ville_id' => 'required_if:type,personnelle|nullable|exists:villes,id',
            'zone_id' => 'required_if:type,personnelle|nullable|exists:zones,id',
            'description' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'nullable|exists:demande_lignes,id',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Update header
        $headerData = [
            'type' => $validated['type'],
            'compte_id' => $validated['compte_id'] ?? null,
            'contact_id' => $validated['contact_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ];
        
        if ($validated['type'] === 'etablissement' && $validated['compte_id']) {
            $compte = Compte::find($validated['compte_id']);
            $headerData['ville_id'] = $compte->ville_id;
            $headerData['zone_id'] = $compte->zone_id;
        } else {
            $headerData['ville_id'] = $validated['ville_id'];
            $headerData['zone_id'] = $validated['zone_id'];
        }
        
        $demandes_specimen->update($headerData);

        // Sync lines
        $existingIds = [];
        foreach ($validated['products'] as $item) {
            if (isset($item['id'])) {
                $line = DemandeLigne::find($item['id']);
                if ($line && $line->demande_id === $demandes_specimen->id) {
                    $line->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ]);
                    $existingIds[] = $line->id;
                }
            } else {
                $newLine = DemandeLigne::create([
                    'demande_id' => $demandes_specimen->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
                $existingIds[] = $newLine->id;
            }
        }
        $demandes_specimen->lignes()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('demandes-specimens.show', $demandes_specimen)
            ->with('success', 'Demande mise à jour.');
    }

    // Delete (only admin or if still demande)
    public function destroy(DemandeSpecimen $demandes_specimen)
    {
        YearLock::check($demandes_specimen);
        $user = Auth::user();
        if ($user->role !== 'admin' && ($demandes_specimen->statut !== 'demande' || $demandes_specimen->delegue_id !== $user->id)) {
            abort(403);
        }
        $demandes_specimen->delete();
        return redirect()->route('demandes-specimens.index')->with('success', 'Demande supprimée.');
    }

    // Generate special BSS number with "SPECIAL" prefix
    private function generateSpecialBssNumber()
    {
        $year = now()->year;
        $lastSpecial = Bss::where('numero', 'like', 'BSS-SPECIAL-' . $year . '-%')
            ->latest('id')
            ->first();

        $increment = 1;
        if ($lastSpecial) {
            $lastNumber = intval(substr($lastSpecial->numero, -4));
            $increment = $lastNumber + 1;
        }

        return 'BSS-SPECIAL-' . $year . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Validation by RBO/Admin
     * 
     * NEW LOGIC:
     * - Validate the special demande request
     * - Create BSS with "SPECIAL" in numero and title
     * - Change demande status to "valide"
     * - After validation, demande fields are locked (read-only)
     * - Can also decline the request
     */
    public function validateRequest(Request $request, DemandeSpecimen $demandes_specimen)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }

        if ($demandes_specimen->statut !== 'demande') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $action = $request->input('action');

        /*
        |--------------------------------------------------------------------------
        | DECLINE
        |--------------------------------------------------------------------------
        */
        if ($action === 'decline') {
            $demandes_specimen->update([
                'statut' => 'decline',
                'valide_par' => $user->id,
                'date_validation' => now(),
            ]);

            return redirect()
                ->route('demandes-specimens.index')
                ->with('success', 'Demande refusée.');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATE SPECIAL REQUEST - CREATE SPECIAL BSS
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($demandes_specimen, $user) {

            // 1. Create Special BSS with "SPECIAL" in numero
            $specialBssNumber = $this->generateSpecialBssNumber();
            
            $specialBss = Bss::create([
                'numero' => $specialBssNumber,
                'compte_id' => $demandes_specimen->compte_id,
                'contact_id' => $demandes_specimen->contact_id,
                'delegate_id' => $demandes_specimen->delegue_id,
                'annee_scolaire_id' => $demandes_specimen->annee_scolaire_id,
                'date_bss' => now()->toDateString(),
                'date_livraison_prevue' => now()->addDays(7)->toDateString(), // Default: 7 days
                'moyen_contact' => 'telephone',
                'statut' => 'valide', // Already validated through demande
                'is_validated_by_rbo' => true,
                'validated_at' => now(),
                'validated_by' => $user->id,
            ]);

            // 2. Add BSS ligne items from demande lignes
            foreach ($demandes_specimen->lignes as $demandeLigne) {
                BssLigne::create([
                    'bss_id' => $specialBss->id,
                    'product_id' => $demandeLigne->product_id,
                    'quantity' => $demandeLigne->quantity,
                ]);
            }

            // 3. Update demande to validated status and link to BSS
            $demandes_specimen->update([
                'statut' => 'valide',
                'valide_par' => $user->id,
                'date_validation' => now(),
                'original_bss_id' => $specialBss->id,
            ]);

            // 4. CREATE COMMERCIAL ACTION (Optional - for tracking)
            $action = Action::create([
                'objet' => 'Livraison Spéciale - ' . $specialBssNumber,
                'compte_id' => $demandes_specimen->compte_id,
                'delegue_id' => $demandes_specimen->delegue_id,
                'date_planification' => now()->toDateString(),
                'heure' => now()->format('H:i'),
                'type' => 'commercial',
                'statut' => 'planifie',
                'module_lie' => 'demande_specimen',
                'module_id' => $demandes_specimen->id,
            ]);

            // 5. CREATE ACTION LINE
            $line = $action->lignes()->create([
                'categorie' => 'Visite',
                'action_type' => 'Livraison Spécimens – Demande Spéciale Validée',
                'moyen' => 'Visite',
                'description' => $demandes_specimen->description,
                'bss_id' => $specialBss->id,
            ]);

            // 6. Link contact if present
            if ($demandes_specimen->contact_id) {
                $line->contacts()->sync([$demandes_specimen->contact_id]);
            }
        });

        return redirect()
            ->route('demandes-specimens.index')
            ->with('success', 'Demande validée. BSS spéciale créée et action commerciale générée.');
    }

    private function generateBssNumber()
    {
        $lastBss = Bss::whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
        $increment = $lastBss ? intval(substr($lastBss->numero, -4)) + 1 : 1;
        return 'BSS-' . now()->year . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }

    private function getUserVilles($user)
    {
        if ($user->role === 'admin') {
            return Ville::orderBy('nom')->get();
        }
        // For delegate or RBO, get villes through their zones
        if ($user->role === 'delegue') {
            $zoneIds = $user->zones->pluck('id');
            return Ville::whereHas('zones', fn($q) => $q->whereIn('zones.id', $zoneIds))->get();
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $zoneIds = Zone::whereIn('rbo_id', [$user->id])->orWhereHas('delegates', fn($q) => $q->whereIn('users.id', $delegateIds))->pluck('id');
            return Ville::whereHas('zones', fn($q) => $q->whereIn('zones.id', $zoneIds))->get();
        }
        return collect();
    }

    private function authorizeView(DemandeSpecimen $demande)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $demande->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($demande->delegue_id)) return;
        }
        abort(403);
    }

    private function authorizeEdit(DemandeSpecimen $demande)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $demande->delegue_id === $user->id && $demande->statut === 'demande') return;
        abort(403);
    }
}
