<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Epreuve;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Action;
use App\Models\ActionLine;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamenController extends Controller
{
    private function getCurrentYear()
    {
        return AnneeScolaire::where('is_active', true)->first() ?? AnneeScolaire::latest('date_debut')->first();
    }

    // Index – list examens with role-based scoping
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Examen::with(['compte', 'contact', 'delegate', 'anneeScolaire']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('compte_id')) $query->where('compte_id', $request->compte_id);
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('annee_scolaire_id')) $query->where('annee_scolaire_id', $request->annee_scolaire_id);

        $examens = $query->orderBy('date_demande', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $statuts = $this->getStatutOptions();

        return view('examens.index', compact('examens', 'comptes', 'years', 'statuts'));
    }

    // Create form
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $currentYear = $this->getCurrentYear();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $langues = ['Français', 'Anglais', 'Arabe', 'Espagnol'];
        $organismes = ['Cambridge Assessment English', 'TOEFL', 'IELTS', 'Other'];
        $niveauxCECR = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'Pre-A1'];
        $niveauxScolaires = ['CP', 'CE1', 'CE2', 'CM1', 'CM2', '6ème', '5ème', '4ème', '3ème', '2ème', '1ère', 'Terminale'];

        return view('examens.create', compact('comptes', 'currentYear', 'years', 'langues', 'organismes', 'niveauxCECR', 'niveauxScolaires'));
    }

    // Store new examen
    public function store(Request $request)
{
    $user = Auth::user();
    if ($user->role !== 'delegue') abort(403);

    $validated = $request->validate([
        'compte_id' => 'required|exists:comptes,id',
        'contact_id' => 'required|exists:contacts,id',
        'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
        'langue' => 'required|string',
        'organisme' => 'required|string',
        'titre' => 'required|string',
        'abreviation' => 'nullable|string',
        'niveau_cecr' => 'nullable|string',
        'niveaux_scolaires' => 'nullable|array',
        'date_demande' => 'required|date',
        'date_examen' => 'nullable|date',
        'description' => 'nullable|string',
        'observations' => 'nullable|string',
        'epreuves' => 'nullable|array',
        'epreuves.*.epreuve' => 'required_with:epreuves|string',
        'epreuves.*.duree' => 'nullable|integer',
        'epreuves.*.date_realisation' => 'nullable|date',
    ]);

    $validated['delegue_id'] = $user->id;
    $validated['statut'] = 'planifie'; // Set status to planifie directly
    $validated['niveaux_scolaires'] = $validated['niveaux_scolaires'] ?? [];

    $examen = Examen::create($validated);

    if (!empty($validated['epreuves'])) {
        foreach ($validated['epreuves'] as $epreuve) {
            $examen->epreuves()->create($epreuve);
        }
    }

    // Automatically create an action
    $this->createActionForExamen($examen);

    return redirect()->route('examens.index')->with('success', 'Demande d\'examen créée.');
}

    //
    private function createActionForExamen(Examen $examen)
{
    $compte = $examen->compte;
    $lieu = 'Zone: ' . ($compte->zone->name ?? 'N/A') . ' - Ville: ' . ($compte->ville->nom ?? 'N/A');

    $action = Action::create([
        'objet' => 'Présentation examen : ' . $examen->titre,
        'compte_id' => $examen->compte_id,
        'delegue_id' => $examen->delegue_id,
        'date_planification' => $examen->date_examen ?? $examen->date_demande,
        'lieu' => $lieu,
        'statut' => 'planifie',
        'type' => 'commercial',
        'module_lie' => 'examen',
        'module_id' => $examen->id,
    ]);

    $actionLine = ActionLine::create([
        'action_id' => $action->id,
        'categorie' => 'Visite',
        'action_type' => 'Visite de Prospection – Présentation Examens',
        'moyen' => 'Visite',
        'description' => 'Présentation de l\'examen ' . $examen->titre,
    ]);

    // Link the contact
    if ($examen->contact_id) {
        $actionLine->contacts()->attach($examen->contact_id);
    }

    // Link the exam (pivot table action_line_examen)
    $actionLine->examens()->attach($examen->id);
}





    // Show detail
    public function show(Examen $examen)
    {
        $this->authorizeView($examen);
        $examen->load('epreuves');
        $statuts = $this->getStatutOptions(); 
        return view('examens.show', compact('examen'));
    }

    // Edit form (only for certain statuts? we allow edit if not closed)
   public function edit(Examen $examen)
{
    $user = Auth::user();
    $this->authorizeEdit($examen);
    $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
    $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
    $langues = ['Français', 'Anglais', 'Arabe', 'Espagnol'];
    $organismes = ['Cambridge Assessment English', 'TOEFL', 'IELTS', 'Other'];
    $niveauxCECR = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'Pre-A1'];
   $niveauxScolaires = ['CP', 'CE1', 'CE2', 'CM1', 'CM2', '6ème', '5ème', '4ème', '3ème', '2ème', '1ère', 'Terminale'];
    $currentYear = $this->getCurrentYear();
    return view('examens.edit', compact('examen', 'comptes', 'years', 'langues', 'organismes', 'niveauxCECR', 'niveauxScolaires', 'currentYear'));
}

    // Update
   public function update(Request $request, Examen $examen)
{
    $this->authorizeEdit($examen);
    $validated = $request->validate([
        'compte_id' => 'required|exists:comptes,id',
        'contact_id' => 'required|exists:contacts,id',
        'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
        'langue' => 'required|string',
        'organisme' => 'required|string',
        'titre' => 'required|string',
        'abreviation' => 'nullable|string',
        'niveau_cecr' => 'nullable|string',
        'niveaux_scolaires' => 'nullable|array',
        'date_demande' => 'required|date',
        'date_examen' => 'nullable|date',
        'description' => 'nullable|string',
        'observations' => 'nullable|string',
        'statut' => 'required|in:' . implode(',', array_keys($this->getStatutOptions())),
        'epreuves' => 'nullable|array',
        'epreuves.*.id' => 'nullable|exists:epreuves,id',
        'epreuves.*.epreuve' => 'required_with:epreuves|string',
        'epreuves.*.duree' => 'nullable|integer',
        'epreuves.*.date_realisation' => 'nullable|date',
    ]);

    $validated['niveaux_scolaires'] = $validated['niveaux_scolaires'] ?? [];
    $oldStatut = $examen->statut;
    $examen->update($validated);

    // Sync epreuves
    $existingIds = [];
    if (!empty($validated['epreuves'])) {
        foreach ($validated['epreuves'] as $epreuve) {
            if (isset($epreuve['id'])) {
                $ep = Epreuve::find($epreuve['id']);
                if ($ep && $ep->examen_id == $examen->id) {
                    $ep->update($epreuve);
                    $existingIds[] = $ep->id;
                }
            } else {
                $new = $examen->epreuves()->create($epreuve);
                $existingIds[] = $new->id;
            }
        }
    }
    $examen->epreuves()->whereNotIn('id', $existingIds)->delete();

    // Create action if status changed to planifie and not already created
    if ($validated['statut'] == 'planifie' && $oldStatut != 'planifie' && !$this->hasAction($examen)) {
        $this->createActionForExamen($examen);
    }

    return redirect()->route('examens.index')->with('success', 'Examen mis à jour.');
}

    // Delete
    public function destroy(Examen $examen)
    {
        $this->authorizeEdit($examen);
        $examen->delete();
        return redirect()->route('examens.index')->with('success', 'Examen supprimé.');
    }

    // Change status (quick action from list or detail)
    public function changeStatus(Request $request, Examen $examen)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']) && $examen->delegate_id !== $user->id) {
            abort(403);
        }
        $request->validate([
            'statut' => 'required|in:' . implode(',', array_keys($this->getStatutOptions()))
        ]);
        $examen->update(['statut' => $request->statut]);
        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    // Helper: statut list
    private function getStatutOptions()
    {
        return [
            'en_attente_feedback' => 'En attente du feedback',
            'avis_favorable' => 'Avis favorable',
            'avis_defavorable' => 'Avis défavorable',
            'signature_convention' => 'Signature de la convention',
            'commande' => 'Commandé',
            'planifie' => 'Planifié',
            'annule' => 'Annulé',
            'decline' => 'Décliné',
            'reporte' => 'Reporté',
            'en_attente_resultats' => 'En attente des résultats',
            'communication_resultats' => 'Communication des résultats électronique',
            'livraison_attestations' => 'Livraison des attestations',
        ];
    }

    private function authorizeView(Examen $examen)
    {
        $user = Auth::user();
    //      dd([
    //     'user_role' => $user->role,
    //     'user_id' => $user->id,
    //     'examen_delegate_id' => $examen->delegue_id,
    //     'is_admin' => $user->role === 'admin',
    //     'is_owner' => $examen->delegue_id === $user->id,
    // ]);
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $examen->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($examen->delegate_id)) return;
        }
        abort(403);
    }

    private function authorizeEdit(Examen $examen)
    {
        if ($examen->anneeScolaire->is_closed && !auth()->user()->isAdmin()) {
            abort(403, 'Cette année scolaire est clôturée.');
        }
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $examen->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($examen->delegue_id)) return;
        }
        abort(403);
    }


    private function hasAction(Examen $examen)
{
    return Action::where('module_lie', 'examen')->where('module_id', $examen->id)->exists();
}
}