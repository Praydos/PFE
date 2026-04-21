<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    private function getCurrentYear()
    {
        return AnneeScolaire::where('is_active', true)->first() ?? AnneeScolaire::latest('date_debut')->first();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Formation::with(['compte', 'contact', 'delegate', 'anneeScolaire']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('type')) $query->where('type', $request->type);

        $formations = $query->orderBy('date_demande', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $statuts = ['demande' => 'Demandée', 'planifiee' => 'Planifiée', 'annulee' => 'Annulée', 'reportee' => 'Reportée', 'realisee' => 'Réalisée'];
        $types = [
            'Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique',
            'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE'
        ];

        return view('formations.index', compact('formations', 'comptes', 'years', 'statuts', 'types'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville', 'zone')->get();
        $currentYear = $this->getCurrentYear();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $types = [
            'Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique',
            'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE'
        ];
        $cibles = ['Direction', 'Enseignants', 'Parents'];

        return view('formations.create', compact('comptes', 'currentYear', 'years', 'types', 'cibles'));
    }

    public function store(Request $request)
{
    $user = Auth::user();
    if ($user->role !== 'delegue') abort(403);

    $validated = $request->validate([
        'compte_id' => 'required|exists:comptes,id',
        'contact_id' => 'required|exists:contacts,id',
        'type' => 'required|in:Formation méthode,Présentation méthode,Accompagnement pédagogique,Leçon modèle,Intégration de classe,Audit de classe,Formation Examen CAMBRIDGE',
        'cible' => 'nullable|in:Direction,Enseignants,Parents',

        // ✅ ADD THIS
        'dates_ecole' => 'required|array',
        'dates_ecole.*' => 'date'

        
    ]);

    $compte = Compte::find($validated['compte_id']);

    $validated['zone_id'] = $compte->zone_id;
    $validated['ville_id'] = $compte->ville_id;
    $validated['delegue_id'] = $user->id;
    $validated['annee_scolaire_id'] = $this->getCurrentYear()->id;

    // store multiple dates
    $validated['date_demande'] = array_filter($validated['dates_ecole']);

    // remove temporary field
    unset($validated['dates_ecole']);

    $validated['statut'] = 'demande';
    $validated['dates_proposees'] = $request->dates_proposees ?? [];

    Formation::create($validated);

    return redirect()->route('formations.index')->with('success', 'Demande de formation créée.');
}

    public function show(Formation $formation)
    {
        $this->authorizeView($formation);
        return view('formations.show', compact('formation'));
    }

    public function edit(Formation $formation)
    {
        $this->authorizeEdit($formation);
        $user = Auth::user();
        $comptes = Compte::where('delegue_id', $user->id)->with('ville', 'zone')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $types = [
            'Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique',
            'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE'
        ];
        $cibles = ['Direction', 'Enseignants', 'Parents'];
        $statuts = ['demande' => 'Demandée', 'planifiee' => 'Planifiée', 'annulee' => 'Annulée', 'reportee' => 'Reportée', 'realisee' => 'Réalisée'];
        $currentYear = $this->getCurrentYear();
        return view('formations.edit', compact('formation', 'comptes', 'years', 'types', 'cibles', 'statuts', 'currentYear'));
    }

    public function update(Request $request, Formation $formation)
{
    $this->authorizeEdit($formation);
    $validated = $request->validate([
        'compte_id' => 'required|exists:comptes,id',
        'contact_id' => 'required|exists:contacts,id',
        'type' => 'required|in:Formation méthode,Présentation méthode,Accompagnement pédagogique,Leçon modèle,Intégration de classe,Audit de classe,Formation Examen CAMBRIDGE',
        'cible' => 'nullable|in:Direction,Enseignants,Parents',
        'dates_proposees' => 'nullable|array',
        'dates_proposees.*' => 'date',
        'statut' => 'required|in:demande,planifiee,annulee,reportee,realisee',
    ]);

    $compte = Compte::find($validated['compte_id']);
    $validated['zone_id'] = $compte->zone_id;
    $validated['ville_id'] = $compte->ville_id;
    $validated['dates_proposees'] = array_filter($validated['dates_proposees'] ?? []);

    $formation->update($validated);
    return redirect()->route('formations.index')->with('success', 'Formation mise à jour.');
}

    public function destroy(Formation $formation)
    {
        $this->authorizeEdit($formation);
        $formation->delete();
        return redirect()->route('formations.index')->with('success', 'Formation supprimée.');
    }

    public function changeStatus(Request $request, Formation $formation)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'rbo' && $formation->delegue_id !== $user->id) {
            abort(403);
        }
        $request->validate([
            'statut' => 'required|in:demande,planifiee,annulee,reportee,realisee'
        ]);
        $formation->update(['statut' => $request->statut]);
        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    private function authorizeView(Formation $formation)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $formation->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($formation->delegue_id)) return;
        }
        abort(403);
    }

    private function authorizeEdit(Formation $formation)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $formation->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($formation->delegue_id)) return;
        }
        abort(403);
    }
}