<?php

namespace App\Http\Controllers;

use App\Models\Effectif;
use App\Models\Compte;
use App\Models\AnneeScolaire;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EffectifController extends Controller
{
    // List effectifs (all roles see their own)
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Effectif::with(['compte', 'anneeScolaire', 'sourceContact1', 'sourceContact2', 'sourceContact3']);

        if ($user->role === 'delegue') {
            $query->whereHas('compte', fn($q) => $q->where('delegue_id', $user->id));
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereHas('compte', fn($q) => $q->whereIn('delegue_id', $delegateIds));
        }
        // admin sees all

        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);
        if ($request->filled('annee_scolaire_id')) $query->where('annee_scolaire_id', $request->annee_scolaire_id);
        if ($request->filled('niveau')) $query->where('niveau', $request->niveau);

        $effectifs = $query->orderBy('compte_id')->orderBy('niveau')->paginate(20);
        $comptes = Compte::orderBy('etablissement')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $niveaux = $this->getNiveauxOptions();

        return view('effectifs.index', compact('effectifs', 'comptes', 'years', 'niveaux'));
    }

    // Create form – accessible to delegate, RBO, admin
    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'delegue', 'rbo'])) abort(403);
        $comptes = ($user->role === 'admin') ? Compte::all() : Compte::where('delegue_id', $user->id)->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $niveaux = $this->getNiveauxOptions();
        $cycleOptions = $this->getCycleOptions();
        return view('effectifs.create', compact('comptes', 'years', 'niveaux', 'cycleOptions'));
    }

    // Store new effectif
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'delegue', 'rbo'])) abort(403);

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'niveau' => 'required|string|max:255',
            'cycle' => 'nullable|string|max:255',
            'massar' => 'nullable|integer|min:0',
            'source_1' => 'nullable|exists:contacts,id',
            'nombre_classes_1' => 'nullable|integer|min:0',
            'source_2' => 'nullable|exists:contacts,id',
            'nombre_classes_2' => 'nullable|integer|min:0',
            'source_3' => 'nullable|exists:contacts,id',
            'nombre_classes_3' => 'nullable|integer|min:0',
        ]);

        $exists = Effectif::where('compte_id', $validated['compte_id'])
            ->where('annee_scolaire_id', $validated['annee_scolaire_id'])
            ->where('niveau', $validated['niveau'])
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['niveau' => 'Ce niveau existe déjà.'])->withInput();
        }

        Effectif::create($validated);
        return redirect()->route('effectifs.index')->with('success', 'Effectif créé.');
    }

    // Edit form – show effectif_valide only for RBO/admin, and only if not validated? Actually we allow edit but with restrictions
    public function edit(Effectif $effectif)
    {
        $user = Auth::user();
        $isOwner = ($user->role === 'delegue' && $effectif->compte->delegue_id === $user->id);
        $isRbo = ($user->role === 'rbo');
        if ($user->role !== 'admin' && !$isOwner && !$isRbo) abort(403);

        // If already validated, only admin can edit (or RBO after devalidation)
        if ($effectif->is_validated && $user->role !== 'admin') {
            return redirect()->route('effectifs.index')->with('error', 'Cet effectif est validé. Seul un admin peut le modifier après annulation.');
        }

        $comptes = ($user->role === 'admin') ? Compte::all() : Compte::where('delegue_id', $user->id)->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $niveaux = $this->getNiveauxOptions();
        $cycleOptions = $this->getCycleOptions();
        return view('effectifs.edit', compact('effectif', 'comptes', 'years', 'niveaux', 'cycleOptions'));
    }

    // Update effectif
    public function update(Request $request, Effectif $effectif)
    {
        $user = Auth::user();
        $isOwner = ($user->role === 'delegue' && $effectif->compte->delegue_id === $user->id);
        $isRbo = ($user->role === 'rbo');
        if ($user->role !== 'admin' && !$isOwner && !$isRbo) abort(403);
        if ($effectif->is_validated && $user->role !== 'admin') {
            return redirect()->back()->with('error', 'Effectif validé – seule validation admin.');
        }

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
            'niveau' => 'required|string|max:255',
            'cycle' => 'nullable|string|max:255',
            'massar' => 'nullable|integer|min:0',
            'source_1' => 'nullable|exists:contacts,id',
            'nombre_classes_1' => 'nullable|integer|min:0',
            'source_2' => 'nullable|exists:contacts,id',
            'nombre_classes_2' => 'nullable|integer|min:0',
            'source_3' => 'nullable|exists:contacts,id',
            'nombre_classes_3' => 'nullable|integer|min:0',
            'effectif_valide' => 'nullable|integer|min:0', // allowed for RBO/admin
        ]);

        // If the row is validated, reset validation flag
        if ($effectif->is_validated) {
            $validated['is_validated'] = false;
            $validated['valide_par'] = null;
        }
        $effectif->update($validated);
        return redirect()->route('effectifs.index')->with('success', 'Effectif mis à jour.');
    }

    // Delete (admin only)
    public function destroy(Effectif $effectif)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $effectif->delete();
        return redirect()->route('effectifs.index')->with('success', 'Ligne supprimée.');
    }

    // Validate row (RBO or Admin)
    public function validateRow(Effectif $effectif)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) abort(403);
        if ($effectif->is_validated) {
            return redirect()->back()->with('warning', 'Déjà validé.');
        }
        $effectif->update([
            'is_validated' => true,
            'valide_par' => $user->id,
        ]);
        return redirect()->route('effectifs.index')->with('success', 'Effectif validé.');
    }

    // De-validate row (Admin only)
    public function devalidateRow(Effectif $effectif)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $effectif->update([
            'is_validated' => false,
            'valide_par' => null,
        ]);
        return redirect()->route('effectifs.index')->with('success', 'Validation annulée.');
    }

    private function getNiveauxOptions()
    {
        return ['CP', 'CE1', 'CE2', '1er', '2ème', '6ème', '5ème', '4ème', '3ème'];
    }

    private function getCycleOptions()
    {
        return ['primaire', 'college', 'Lycée', 'Learners', 'Pre-teens', 'Teens', 'Adults'];
    }
}