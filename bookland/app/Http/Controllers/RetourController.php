<?php

namespace App\Http\Controllers;

use App\Models\Bss;
use App\Models\BssLigne;
use App\Models\Retour;
use App\Models\Consignation;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use App\Models\Action;
use App\Models\ActionLine;
use App\Models\Compte;
use Illuminate\Support\Facades\Auth;

class RetourController extends Controller
{
    private function getCurrentYear()
    {
        return AnneeScolaire::where('is_active', true)->first() ?? AnneeScolaire::latest('date_debut')->first();
    }


    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Retour::with(['bss', 'createdBy', 'lignes.product']);

        // Role-based filtering
        if ($user->role === 'delegue') {
            $query->whereHas('bss', fn($q) => $q->where('delegate_id', $user->id));
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereHas('bss', fn($q) => $q->whereIn('delegate_id', $delegateIds));
        }
        // Admin sees all

        // Optional search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                ->orWhereHas('bss', fn($q2) => $q2->where('numero', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('bss_id')) {
            $query->where('bss_id', $request->bss_id);
        }

        $retours = $query->orderBy('created_at', 'desc')->paginate(15);
        $bssList = Bss::orderBy('numero')->get(); // for filter dropdown

        return view('retours.index', compact('retours', 'bssList'));
    }














    public function create(Bss $bss)
    {
        $user = Auth::user();
        // Only delegate who created the BSS can return items, and only if BSS is 'livre'
        if ($user->role !== 'delegue' || $bss->delegate_id !== $user->id || $bss->statut !== 'livre') {
            abort(403);
        }

        $bss->load('lignes.product');
        // Only lines that are 'livree' can be returned
        $returnableLines = $bss->lignes->filter(fn($l) => $l->statut_ligne === 'livree');

        // Generate unique return number
        $lastRetour = Retour::whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
        $increment = $lastRetour ? intval(substr($lastRetour->numero, -4)) + 1 : 1;
        $numero = 'RET-' . now()->year . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);

        return view('retours.create', compact('bss', 'returnableLines', 'numero'));
    }

    public function store(Request $request, Bss $bss)
{
    $user = Auth::user();
    if ($user->role !== 'delegue' || $bss->delegate_id !== $user->id || $bss->statut !== 'livre') {
        abort(403);
    }

    $validated = $request->validate([
        'numero' => 'required|unique:retours,numero',
        'date_retour' => 'required|date',
        'motif' => 'nullable|string',
        'lignes' => 'required|array|min:1',
        'lignes.*.id' => 'required|exists:bss_lignes,id',
        'lignes.*.quantite' => 'required|integer|min:1',
    ]);

    $currentYear = $this->getCurrentYear();
    if (!$currentYear) {
        return redirect()->back()->withErrors(['error' => 'Année scolaire non trouvée.']);
    }

    $retour = Retour::create([
        'numero' => $validated['numero'],
        'bss_id' => $bss->id,
        'date_retour' => $validated['date_retour'],
        'created_by' => $user->id,
        'motif' => $validated['motif'],
    ]);

    $returnedProductIds = [];

    foreach ($validated['lignes'] as $item) {
        $ligne = BssLigne::find($item['id']);
        if ($item['quantite'] > $ligne->quantity) {
            return redirect()->back()->withErrors(['quantite' => "Quantité retournée supérieure à la quantité livrée."]);
        }
        $retour->lignes()->attach($ligne->id, ['quantite_retournee' => $item['quantite']]);

        if ($item['quantite'] == $ligne->quantity) {
            $ligne->update(['statut_ligne' => 'retournee']);
        }

        if ($ligne->source === 'consignation') {
            $consignation = Consignation::firstOrCreate([
                'delegate_id' => $user->id,
                'product_id' => $ligne->product_id,
                'annee_scolaire_id' => $currentYear->id,
            ], ['quantity' => 0]);
            $consignation->increment('quantity', $item['quantite']);
        }

        $returnedProductIds[] = $ligne->product_id;
    }

    // Update BSS status to 'retour'
    $bss->update(['statut' => 'retour']);

    // Create an action for the return
    $compte = Compte::with(['zone', 'ville'])->find($bss->compte_id);
    $lieu = 'Zone: ' . ($compte->zone->name ?? 'N/A') . ' - Ville: ' . ($compte->ville->nom ?? 'N/A');

    $action = Action::create([
        'objet' => 'Retour BSS ' . $bss->numero,
        'compte_id' => $bss->compte_id,
        'delegue_id' => $user->id,
        'date_planification' => now(),
        'lieu' => $lieu,
        'statut' => 'planifie',
        'type' => 'commercial',
        'module_lie' => 'retour',
        'module_id' => $retour->id,
    ]);

    $actionLine = ActionLine::create([
        'action_id' => $action->id,
        'categorie' => 'Visite',
        'action_type' => 'Retour Spécimens',
        'moyen' => 'Visite',
        'description' => 'Retour BSS ' . $bss->numero,
    ]);

    // Attach contact
    if ($bss->contact_id) {
        $actionLine->contacts()->attach($bss->contact_id);
    }

    // Attach returned products (unique)
    $uniqueProductIds = array_unique($returnedProductIds);
    $actionLine->products()->attach($uniqueProductIds);

    return redirect()->route('bss.show', $bss)->with('success', 'Bon de retour créé, BSS marqué comme retourné.');
}
}