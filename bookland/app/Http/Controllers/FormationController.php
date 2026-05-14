<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Zone;
use App\Models\Ville;
use App\Models\AnneeScolaire;
use App\Models\User;
use App\Support\YearLock;
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
        }
        elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('compte_id'))
            $query->where('compte_id', $request->compte_id);
        if ($request->filled('statut'))
            $query->where('statut', $request->statut);
        if ($request->filled('type'))
            $query->where('type', $request->type);

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



    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue')
            abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville', 'zone')->get();
        $currentYear = $this->getCurrentYear();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $types = [
            'Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique',
            'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE'
        ];


        $cibles = ['Direction', 'Enseignants', 'Parents'];

        $villes = $this->getUserVilles($user); // helper
        $zones = Zone::all();

        // Pre‑selected compte from query parameter
        $selectedCompteId = request('compte_id');
        $defaultVilleId = null;
        $defaultZoneId = null;
        if ($selectedCompteId && $comptes->contains('id', $selectedCompteId)) {
            $compte = $comptes->find($selectedCompteId);
            $defaultVilleId = $compte->ville_id;
            $defaultZoneId = $compte->zone_id;
        }

        $defaultDate = $request->get('date_demande', now()->toDateString());

        return view('formations.create', compact('comptes', 'currentYear', 'years', 'types', 'cibles', 'villes', 'zones', 'selectedCompteId', 'defaultVilleId', 'defaultZoneId', 'defaultDate'));
    }

    public function store(Request $request)    {
        $user = Auth::user();
    if ($user->role !== 'delegue') abort(403);

    // Filter empty dates
    if ($request->has('dates_ecole')) {
        $request->merge([
            'dates_ecole' => array_filter($request->input('dates_ecole', []), fn($d) => !empty($d))
        ]);
    }
    if ($request->has('dates_proposees')) {
        $request->merge([
            'dates_proposees' => array_filter($request->input('dates_proposees', []), fn($d) => !empty($d))
        ]);
    }

        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'contact_id' => 'required|exists:contacts,id',
            'ville_id' => 'required|exists:villes,id',
            'zone_id' => 'required|exists:zones,id',
            'type' => 'required|in:Formation méthode,Présentation méthode,Accompagnement pédagogique,Leçon modèle,Intégration de classe,Audit de classe,Formation Examen CAMBRIDGE',
            'cible' => 'nullable|in:Direction,Enseignants,Parents',
            'dates_ecole' => 'nullable|array',
            'dates_ecole.*' => 'date',
            'dates_proposees' => 'nullable|array',
            'dates_proposees.*' => 'date',
        ]);

        $compte = Compte::findOrFail($validated['compte_id']);
        $ownerDelegueId = (int) $compte->delegue_id;
        if ($ownerDelegueId !== (int) $user->id) {
            abort(403);
        }

        // Prepare data for creation
        $data = [
            'compte_id' => $validated['compte_id'],
            'contact_id' => $validated['contact_id'],
            'ville_id' => $validated['ville_id'],
            'zone_id' => $validated['zone_id'],
            'type' => $validated['type'],
            'cible' => $validated['cible'] ?? null,
            'delegue_id' => $ownerDelegueId,
            'annee_scolaire_id' => $this->getCurrentYear()->id,
            'statut' => 'demande',
            'date_demande' => $validated['dates_ecole'] ?? [], // JSON array
            'dates_proposees' => $validated['dates_proposees'] ?? [], // JSON array (nullable)
        ];

        Formation::create($data);

        return redirect()->route('formations.index')->with('success', 'Demande de formation créée.');    }
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
        $villes = $this->getUserVilles($user); // helper
        $zones = Zone::all();
        $types = [
            'Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique',
            'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE'
        ];
        $cibles = ['Direction', 'Enseignants', 'Parents'];
        $statuts = ['demande' => 'Demandée', 'planifiee' => 'Planifiée', 'annulee' => 'Annulée', 'reportee' => 'Reportée', 'realisee' => 'Réalisée'];
        $currentYear = $this->getCurrentYear();
        return view('formations.edit', compact('formation', 'comptes', 'years', 'types', 'cibles', 'statuts', 'currentYear', 'villes', 'zones'));
    }

    public function update(Request $request, Formation $formation)    {
        YearLock::check($formation);
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
        return redirect()->route('formations.index')->with('success', 'Formation mise à jour.');    }

    public function destroy(Formation $formation)
    {
        YearLock::check($formation);
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
        if ($user->role === 'admin')
            return;
        if ($user->role === 'delegue' && $formation->delegue_id === $user->id)
            return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($formation->delegue_id))
                return;
        }
        abort(403);
    }

    private function authorizeEdit(Formation $formation)
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if ($user->role === 'delegue' && $formation->delegue_id === $user->id)
            return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($formation->delegue_id))
                return;
        }
        abort(403);
    }

    // ── For-delegate flow (RBO / Admin) ──────────────────────────────────

    public function createForDelegate(Request $request, User $delegate)
    {
        $this->authorizeForDelegate($delegate);

        $comptes     = Compte::where('delegue_id', $delegate->id)->with('ville', 'zone')->get();
        $currentYear = $this->getCurrentYear();
        $years       = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $types = [
            'Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique',
            'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE'
        ];
        $cibles = ['Direction', 'Enseignants', 'Parents'];
        $villes = $this->getUserVilles($delegate);
        $zones  = Zone::all();

        $selectedCompteId = $request->get('compte_id');
        $defaultVilleId   = null;
        $defaultZoneId    = null;
        if ($selectedCompteId && $comptes->contains('id', $selectedCompteId)) {
            $compte = $comptes->find($selectedCompteId);
            $defaultVilleId = $compte->ville_id;
            $defaultZoneId  = $compte->zone_id;
        }
        $defaultDate    = $request->get('date_demande', now()->toDateString());
        $targetDelegate = $delegate;

        return view('formations.create', compact(
            'comptes', 'currentYear', 'years', 'types', 'cibles', 'villes', 'zones',
            'selectedCompteId', 'defaultVilleId', 'defaultZoneId', 'defaultDate', 'targetDelegate'
        ));
    }

    public function storeForDelegate(Request $request, User $delegate)
{
    $this->authorizeForDelegate($delegate);

    // 🔥 Filter empty dates before validation
    if ($request->has('dates_ecole')) {
        $request->merge([
            'dates_ecole' => array_filter($request->input('dates_ecole', []), fn($d) => !empty($d))
        ]);
    }
    if ($request->has('dates_proposees')) {
        $request->merge([
            'dates_proposees' => array_filter($request->input('dates_proposees', []), fn($d) => !empty($d))
        ]);
    }

    $validated = $request->validate([
        'compte_id'         => 'required|exists:comptes,id',
        'contact_id'        => 'required|exists:contacts,id',
        'ville_id'          => 'required|exists:villes,id',
        'zone_id'           => 'required|exists:zones,id',
        'type'              => 'required|in:Formation méthode,Présentation méthode,Accompagnement pédagogique,Leçon modèle,Intégration de classe,Audit de classe,Formation Examen CAMBRIDGE',
        'cible'             => 'nullable|in:Direction,Enseignants,Parents',
        'dates_ecole'       => 'nullable|array',
        'dates_ecole.*'     => 'date',
        'dates_proposees'   => 'nullable|array',
        'dates_proposees.*' => 'date',
    ]);

    Formation::create([
        'compte_id'         => $validated['compte_id'],
        'contact_id'        => $validated['contact_id'],
        'ville_id'          => $validated['ville_id'],
        'zone_id'           => $validated['zone_id'],
        'type'              => $validated['type'],
        'cible'             => $validated['cible'] ?? null,
        'delegue_id'        => $delegate->id,
        'annee_scolaire_id' => $this->getCurrentYear()->id,
        'statut'            => 'demande',
        'date_demande'      => $validated['dates_ecole'] ?? [],      // JSON array
        'dates_proposees'   => $validated['dates_proposees'] ?? [],  // JSON array
    ]);

    return redirect()->route('formations.index')
        ->with('success', 'Demande de formation créée pour ' . $delegate->prenom . ' ' . $delegate->nom . '.');
}

    private function authorizeForDelegate(User $delegate): void
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($delegate->id)) return;
        }
        abort(403, 'Non autorisé à créer des formations pour ce délégué.');
    }
}