<?php

namespace App\Http\Controllers;

use App\Models\Consignation;
use App\Models\Product;
use App\Models\User;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsignationController extends Controller
{
    // Helper to get current active year (or fallback)
    private function getCurrentYear()
    {
        $year = AnneeScolaire::where('is_active', true)->first();
        if (!$year) {
            // fallback to the latest year
            $year = AnneeScolaire::orderBy('date_debut', 'desc')->first();
        }
        return $year;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Consignation::with(['delegate', 'product', 'anneeScolaire']);

        // Role-based filtering
        if ($user->role === 'delegue') {
            $query->where('delegate_id', $user->id);
        } elseif ($user->role === 'rbo') {
            // RBO sees consignations of delegates they supervise
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegate_id', $delegateIds);
        }
        // Admin sees all

        // Filter by delegate (only for admin/RBO)
        if ($user->role !== 'delegue' && $request->filled('delegate_id')) {
            $query->where('delegate_id', $request->delegate_id);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by year
        $currentYear = $this->getCurrentYear();
        $yearId = $request->filled('annee_scolaire_id') ? $request->annee_scolaire_id : ($currentYear ? $currentYear->id : null);
        if ($yearId) {
            $query->where('annee_scolaire_id', $yearId);
        }

        $consignations = $query->orderBy('delegate_id')->paginate(20);

        // For filters
        $delegates = User::where('role', 'delegue')->orderBy('nom')->get();
        $products = Product::orderBy('titre')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();

        return view('consignations.index', compact('consignations', 'delegates', 'products', 'years', 'yearId'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $delegates = User::where('role', 'delegue')->orderBy('nom')->get();
        $products = Product::orderBy('titre')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        return view('consignations.create', compact('delegates', 'products', 'years'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'delegate_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'quantity' => 'required|integer|min:0',
        ]);

        // Check if entry already exists
        $existing = Consignation::where($validated)->first();
        if ($existing) {
            return redirect()->back()->withErrors(['product_id' => 'Cette combinaison existe déjà. Utilisez la modification.']);
        }

        Consignation::create($validated);
        return redirect()->route('consignations.index')->with('success', 'Stock ajouté.');
    }

    public function edit(Consignation $consignation)
    {
        $this->authorizeAdmin();
        $delegates = User::where('role', 'delegue')->orderBy('nom')->get();
        $products = Product::orderBy('titre')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        return view('consignations.edit', compact('consignation', 'delegates', 'products', 'years'));
    }

    public function update(Request $request, Consignation $consignation)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'delegate_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $consignation->update($validated);
        return redirect()->route('consignations.index')->with('success', 'Stock mis à jour.');
    }

    public function destroy(Consignation $consignation)
    {
        $this->authorizeAdmin();
        $consignation->delete();
        return redirect()->route('consignations.index')->with('success', 'Ligne supprimée.');
    }

    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
    }

    // Placeholder for BSS creation (to be implemented later)
    public function createBss(Consignation $consignation)
    {
        // TODO: will redirect to BSS creation form with pre‑selected product and delegate
        return redirect()->route('bss.create', [
            'product_id' => $consignation->product_id,
            'delegate_id' => $consignation->delegate_id,
            'quantity' => $consignation->quantity
        ])->with('info', 'Fonction à venir – création de BSS.');
    }
}