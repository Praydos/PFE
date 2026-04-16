<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\BssLigne;
use App\Models\Compte;
use App\Models\Product;
use App\Models\Contact;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdoptionController extends Controller
{
    private function getCurrentYear()
    {
        return AnneeScolaire::where('is_active', true)->first() ?? AnneeScolaire::latest('date_debut')->first();
    }

    // List adoptions (role‑based)
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Adoption::with(['compte', 'product', 'anneeScolaire', 'delegate', 'bssLigne']);

        if ($user->role === 'delegue') {
            $query->where('delegate_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegate_id', $delegateIds);
        }

        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);
        if ($request->filled('annee_scolaire_id')) $query->where('annee_scolaire_id', $request->annee_scolaire_id);

        $adoptions = $query->orderBy('date_adoption', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();

        return view('adoptions.index', compact('adoptions', 'comptes', 'years'));
    }

    // Manual adoption – create form (only delegates)
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $products = Product::orderBy('titre')->get();
        $currentYear = $this->getCurrentYear();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();

        if (!$currentYear) return redirect()->back()->withErrors(['error' => 'Aucune année scolaire active.']);

        $contacts = collect();
        return view('adoptions.create', compact('comptes', 'products', 'currentYear', 'years', 'contacts'));
    }

    // Store manual adoption
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'product_id' => 'required|exists:products,id',
            'contact_id' => 'required|exists:contacts,id',
            'methode' => 'required|string|max:255',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'quantity' => 'required|integer|min:1',
            'date_adoption' => 'required|date',
            'niveau' => 'required|string|max:255',          // <-- changed from niveau_scolaire
            'cycle' => 'required|string|max:255',           // <-- added cycle validation
        ]);

        $exists = Adoption::where('compte_id', $validated['compte_id'])
            ->where('product_id', $validated['product_id'])
            ->where('annee_scolaire_id', $validated['annee_scolaire_id'])
            ->exists();

        if ($exists) return redirect()->back()->withErrors(['product_id' => 'Ce produit a déjà été adopté pour ce compte cette année.'])->withInput();

        Adoption::create([
            'compte_id' => $validated['compte_id'],
            'product_id' => $validated['product_id'],
            'contact_id' => $validated['contact_id'],
            'methode' => $validated['methode'],
            'annee_scolaire_id' => $validated['annee_scolaire_id'],
            'quantity' => $validated['quantity'],
            'date_adoption' => $validated['date_adoption'],
            'delegate_id' => $user->id,
            'niveau' => $validated['niveau'],
            'cycle' => $validated['cycle'],
            'bss_ligne_id' => null,
        ]);

        return redirect()->route('adoptions.index')->with('success', 'Adoption enregistrée.');
    }

    // Convert a BSS line into an adoption (show pre‑filled form)
    public function convertFromBss(BssLigne $bssLigne)
    {
        $user = Auth::user();
        $bss = $bssLigne->bss;

        if ($user->role !== 'delegue' || $bss->delegate_id !== $user->id || $bss->statut !== 'livre') abort(403);
        if ($bssLigne->adoption) return redirect()->back()->with('error', 'Ce spécimen a déjà été converti en adoption.');

        $currentYear = $this->getCurrentYear();
        if (!$currentYear) return redirect()->back()->withErrors(['error' => 'Aucune année scolaire active.']);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $products = Product::all();
        $defaultCompteId = $bss->compte_id;
        $defaultProductId = $bssLigne->product_id;
        $defaultQuantity = $bssLigne->quantity;
        $defaultDate = now()->toDateString();
        $defaultNiveau = null;
        $defaultCycle = null;
        $defaultContactId = $bss->contact_id;
        $contacts = Contact::whereHas('comptes', fn($q) => $q->where('comptes.id', $bss->compte_id))->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();

        return view('adoptions.convert', compact(
            'bssLigne', 'comptes', 'products', 'currentYear', 'years', 'defaultContactId', 'contacts',
            'defaultCompteId', 'defaultProductId', 'defaultQuantity', 'defaultDate', 'defaultNiveau', 'defaultCycle'
        ));
    }

    // Store adoption from BSS conversion
    public function storeFromBss(Request $request, BssLigne $bssLigne)
    {
        $user = Auth::user();
        $bss = $bssLigne->bss;
        if ($user->role !== 'delegue' || $bss->delegate_id !== $user->id || $bss->statut !== 'livre') abort(403);
        if ($bssLigne->adoption) return redirect()->back()->with('error', 'Déjà converti.');

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'product_id' => 'required|exists:products,id',
            'contact_id' => 'required|exists:contacts,id',
            'methode' => 'required|string|max:255',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'quantity' => 'required|integer|min:1',
            'date_adoption' => 'required|date',
            'niveau' => 'required|string|max:255',
            'cycle' => 'required|string|max:255',
        ]);

        $exists = Adoption::where('compte_id', $validated['compte_id'])
            ->where('product_id', $validated['product_id'])
            ->where('annee_scolaire_id', $validated['annee_scolaire_id'])
            ->exists();

        if ($exists) return redirect()->back()->withErrors(['product_id' => 'Ce produit a déjà été adopté pour ce compte cette année.'])->withInput();

        $adoption = Adoption::create([
            'compte_id' => $validated['compte_id'],
            'product_id' => $validated['product_id'],
            'contact_id' => $validated['contact_id'],
            'methode' => $validated['methode'],
            'annee_scolaire_id' => $validated['annee_scolaire_id'],
            'quantity' => $validated['quantity'],
            'date_adoption' => $validated['date_adoption'],
            'delegate_id' => $user->id,
            'niveau' => $validated['niveau'],
            'cycle' => $validated['cycle'],
            'bss_ligne_id' => $bssLigne->id,
        ]);

        return redirect()->route('adoptions.index')->with('success', 'Adoption créée à partir du BSS.');
    }

    // Show a single adoption (detail view)
    public function show(Adoption $adoption)
    {
        $this->authorizeView($adoption);
        return view('adoptions.show', compact('adoption'));
    }

    // Edit adoption (only delegate who created it)
    public function edit(Adoption $adoption)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' || $adoption->delegate_id !== $user->id) abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $products = Product::orderBy('titre')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $currentYear = $this->getCurrentYear();
        $contacts = Contact::whereHas('comptes', fn($q) => $q->where('comptes.id', $adoption->compte_id))->get();

        return view('adoptions.edit', compact('adoption', 'comptes', 'products', 'years', 'currentYear', 'contacts'));
    }

    // Update adoption
    public function update(Request $request, Adoption $adoption)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' || $adoption->delegate_id !== $user->id) abort(403);

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'product_id' => 'required|exists:products,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'quantity' => 'required|integer|min:1',
            'date_adoption' => 'required|date',
            'niveau' => 'required|string|max:255',
            'cycle' => 'required|string|max:255',
        ]);

        $adoption->update($validated);
        return redirect()->route('adoptions.index')->with('success', 'Adoption mise à jour.');
    }

    // Delete adoption
    public function destroy(Adoption $adoption)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' || $adoption->delegate_id !== $user->id) abort(403);
        $adoption->delete();
        return redirect()->route('adoptions.index')->with('success', 'Adoption supprimée.');
    }

    // ------------------------------------------------------------------
    // API: Return list of niveaux allowed for a given compte
    // ------------------------------------------------------------------
    public function getNiveauxByCompte(Compte $compte)
    {
        $niveaux = [];

        if ($compte->type === 'ecole') {
            switch ($compte->cycle) {
                case 'Maternelle':
                    $niveaux = ['CP', 'CE1', 'CE2'];
                    break;
                case 'Primaire':
                    $niveaux = ['CP', 'CE1', 'CE2', '1er', '2ème', '3ème', '4ème', '5ème', '6ème'];
                    break;
                case 'Collège':
                    $niveaux = ['6ème', '5ème', '4ème', '3ème'];
                    break;
                case 'Lycée':
                    $niveaux = ['1er', '2ème', '3ème'];
                    break;
                default:
                    $niveaux = ['CP', 'CE1', 'CE2', '1er', '2ème', '6ème', '5ème', '4ème', '3ème'];
            }
        } else {
            $niveaux = ['CP', 'CE1', 'CE2', '1er', '2ème', '6ème', '5ème', '4ème', '3ème'];
        }

        return response()->json($niveaux);
    }

    // ------------------------------------------------------------------
    // API: Fetch effectif_valide for the given compte, year, niveau and cycle
    // ------------------------------------------------------------------
    public function getEffectifByNiveau(Request $request, Compte $compte)
    {
        $request->validate([
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'niveau' => 'required|string|max:255',
            'cycle' => 'required|string|max:255',
        ]);

        $effectif = $compte->effectifs()
            ->where('annee_scolaire_id', $request->annee_scolaire_id)
            ->where('niveau', $request->niveau)
            ->where('cycle', $request->cycle)
            ->first();

        return response()->json([
            'effectif_valide' => $effectif ? $effectif->effectif_valide : null,
            'is_validated' => $effectif ? $effectif->is_validated : false,
        ]);
    }

    // ------------------------------------------------------------------
    // Authorization helper for show()
    // ------------------------------------------------------------------
    private function authorizeView(Adoption $adoption)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $adoption->delegate_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($adoption->delegate_id)) return;
        }
        abort(403);
    }
}