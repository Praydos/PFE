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
use Illuminate\Http\Request;
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
        $query = DemandeSpecimen::with(['compte', 'contact', 'delegate', 'ville', 'zone', 'bss']);

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
    if ($user->role !== 'delegue') abort(403);

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
        // We also need to pre‑select the contact? Not necessary, the AJAX will load them.
    }

    return view('demandes_specimens.create', compact('comptes', 'products', 'currentYear', 'years', 'villes', 'zones', 'selectedCompteId', 'defaultVilleId', 'defaultZoneId'));
}
    // Store new request
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

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
        } else {
            $villeId = $validated['ville_id'];
            $zoneId = $validated['zone_id'];
        }

        $linkedBss = null;

        foreach ($validated['products'] as $item) {

            $existingLine = BssLigne::where('product_id', $item['product_id'])
                ->whereHas('bss', function ($q) use ($validated, $yearIds) {
                    $q->where('compte_id', $validated['compte_id'])
                    ->whereIn('annee_scolaire_id', $yearIds)
                    ->whereIn('statut', ['valide', 'livre']);
                })
                ->with('bss')
                ->first();

            if ($existingLine) {
                $linkedBss = $existingLine->bss;
                break;
            }
        }

        if (!$linkedBss) {
            return back()->withErrors([
                'products' => 'Aucune livraison précédente trouvée. Veuillez créer un BSS normal.'
            ]);
        }

        $demande = DemandeSpecimen::create([
            'type' => $validated['type'],
            'compte_id' => $validated['compte_id'] ?? null,
            'contact_id' => $validated['contact_id'] ?? null,
            'delegue_id' => $user->id,
            'annee_scolaire_id' => $currentYear->id,
            'ville_id' => $villeId,
            'zone_id' => $zoneId,
            'date_demande' => now()->toDateString(),
            'description' => $validated['description'] ?? null,
            'statut' => 'demande',
            'original_bss_id' => $linkedBss->id,
        ]);

        foreach ($validated['products'] as $item) {
            DemandeLigne::create([
                'demande_id' => $demande->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('demandes-specimens.index')->with('success', 'Demande créée.');
    }

    // Show detail
    public function show(DemandeSpecimen $demandes_specimen)
    {
        $this->authorizeView($demandes_specimen);
        $demandes_specimen->load('lignes.product', 'compte', 'contact', 'ville', 'zone', 'bss');
        return view('demandes_specimens.show', compact('demandes_specimen'));
    }

    // Edit (only for demande status)
    public function edit(DemandeSpecimen $demandes_specimen)
    {
        $this->authorizeEdit($demandes_specimen);
        if ($demandes_specimen->statut !== 'demande') {
            return redirect()->route('demandes-specimens.index')->with('error', 'Seules les demandes en attente peuvent être modifiées.');
        }
        $user = Auth::user();
        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $products = Product::orderBy('titre')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $villes = $this->getUserVilles($user);
        $zones = Zone::all();
        return view('demandes_specimens.edit', compact('demandes_specimen', 'comptes', 'products', 'years', 'villes', 'zones'));
    }

    // Update
    public function update(Request $request, DemandeSpecimen $demandes_specimen)
    {
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

        return redirect()->route('demandes-specimens.show', $demandes_specimen)->with('success', 'Demande mise à jour.');
    }

    // Delete (only admin or if still demande)
    public function destroy(DemandeSpecimen $demandes_specimen)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && ($demandes_specimen->statut !== 'demande' || $demandes_specimen->delegue_id !== $user->id)) {
            abort(403);
        }
        $demandes_specimen->delete();
        return redirect()->route('demandes-specimens.index')->with('success', 'Demande supprimée.');
    }

    // Validation by RBO/Admin
    public function validateRequest(Request $request, DemandeSpecimen $demandes_specimen)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) abort(403);
        if ($demandes_specimen->statut !== 'demande') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $action = $request->input('action');
        if ($action === 'decline') {
            $demandes_specimen->update([
                'statut' => 'decline',
                'valide_par' => $user->id,
                'date_validation' => now(),
            ]);
            return redirect()->route('demandes-specimens.index')->with('success', 'Demande refusée.');
        }

        // Approve: generate BSS
        // We must create a BSS with source = 'magasin'
        $bssData = [
            'numero' => $this->generateBssNumber(),
            'compte_id' => $demandes_specimen->compte_id,
            'contact_id' => $demandes_specimen->contact_id,
            'delegate_id' => $demandes_specimen->delegue_id,
            'annee_scolaire_id' => $demandes_specimen->annee_scolaire_id,
            'date_bss' => now(),
            'statut' => 'valide', // already approved, no need for further validation
            'source' => 'magasin',
        ];
        $bss = Bss::create($bssData);

        foreach ($demandes_specimen->lignes as $ligne) {
            BssLigne::create([
                'bss_id' => $bss->id,
                'product_id' => $ligne->product_id,
                'quantity' => $ligne->quantity,
                'source' => 'magasin',
                'statut_ligne' => 'en_attente',
            ]);
        }

        // Link BSS to demande
        $demandes_specimen->update([
            'statut' => 'valide',
            'valide_par' => $user->id,
            'date_validation' => now(),
            'generated_bss_id' => $bss->id,
        ]);

        return redirect()->route('demandes-specimens.index')->with('success', 'Demande approuvée. BSS généré : ' . $bss->numero);
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