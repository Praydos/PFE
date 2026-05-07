<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Compte;
use App\Models\Ville;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\CompteContact;   

use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Contact::with(['ville', 'comptes']);

        // Role-based scoping
        if ($user->role === 'delegue') {
            // Delegate sees contacts linked to comptes they manage
            $query->whereHas('comptes', function ($q) use ($user) {
                $q->where('delegue_id', $user->id);
            });
        } elseif ($user->role === 'rbo') {
            // RBO sees contacts linked to comptes of delegates they supervise
            // A RBO supervises zones → zones have delegates → comptes have delegue_id in that set
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereHas('comptes', function ($q) use ($delegateIds) {
                $q->whereIn('delegue_id', $delegateIds);
            });
        }
        // Admin sees all, no extra condition

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        $contacts = $query->paginate(15);
        return view('contacts.index', compact('contacts'));
    }
    //create, store, edit, update, destroy methods...

    public function create()
    {
        $villes = Ville::all();

        $categoriesOptions = [
            'Gestion des Contacts Clients',
            'Gestion des Contacts Editeurs',
            'Gestion des Contacts Formateurs',
            'Gestion des Contacts Collaborateurs'
        ];

        $cyclesOptions = [
            'Creche', 'Maternelle', 'Primaire', 'Collège', 'Lycée', 'Supérieur',
            'Very Young Learners', 'Kids', 'Pre-teens', 'Teens', 'Adults'
        ];
        

        return view('contacts.create', compact('villes', 'categoriesOptions', 'cyclesOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:contacts,email',
            'telephone' => 'nullable|string|max:20',
            'ville_id' => 'required|exists:villes,id',
            'civilite' => 'nullable|in:M.,Mme,Mlle',
            'fonction' => 'nullable|in:Directeur,Responsable,Enseignant,Autre',
            'categories' => 'nullable|array',
            'categories.*' => 'string',
            'cycles' => 'nullable|array',
            'cycles.*' => 'string',
        ]);

        $contact = Contact::create(array_merge(
            $validated,
            [
                'categories' => $validated['categories'] ?? [],
                'cycles' => $validated['cycles'] ?? [],
            ]
        ));

        return redirect()->route('contacts.index')
            ->with('success', 'Contact créé avec succès.');
    }


    public function show(Contact $contact)
{
    $user = Auth::user();
 
    // ── Access control ─────────────────────────────────────────────────────
    if ($user->role === 'delegue') {
        // Delegate can only view contacts linked to their own comptes
        $hasAccess = $contact->comptes()->where('delegue_id', $user->id)->exists();
        if (!$hasAccess) abort(403);
 
    } elseif ($user->role === 'rbo') {
        // RBO can view contacts linked to comptes of delegates they supervise
        $delegateIds = $this->getRboDelegueIds($user);
        $hasAccess = $contact->comptes()
            ->whereIn('delegue_id', $delegateIds)
            ->exists();
        if (!$hasAccess) abort(403);
    }
    // Admin: no restriction
 
    // ── Load relationships ──────────────────────────────────────────────────
    $contact->load([
        'ville',
        'comptes.ville',
        // Load events through the pivot, ordered most-recent first
        'events' => function ($q) {
            $q->with(['ville', 'anneeScolaire', 'delegate'])
              ->orderBy('date_event', 'desc');
        },
    ]);
 
    // ── Aggregate stats for the event history card ──────────────────────────
    $eventStats = [
        'total'    => $contact->events->count(),
        'present'  => $contact->events->where('pivot.statut', 'present')->count(),
        'accepte'  => $contact->events->where('pivot.statut', 'accepte')->count(),
        'decline'  => $contact->events->where('pivot.statut', 'decline')->count(),
        'invite'   => $contact->events->where('pivot.statut', 'invite')->count(),
        'rate'     => 0,
    ];
 
    if ($eventStats['total'] > 0) {
        $eventStats['rate'] = round(
            ($eventStats['present'] / $eventStats['total']) * 100,
            1
        );
    }
 
    // Human-readable statut labels
    $statuts = [
        'invite'  => 'Invité',
        'accepte' => 'Accepté',
        'decline' => 'Décliné',
        'present' => 'Présent',
    ];
 
    return view('contacts.show', compact('contact', 'eventStats', 'statuts'));
}



    public function edit(Contact $contact)
    {
        $villes = Ville::all();
        $categoriesOptions = [
            'Gestion des Contacts Clients',
            'Gestion des Contacts Editeurs',
            'Gestion des Contacts Formateurs',
            'Gestion des Contacts Collaborateurs'
        ];
        $cyclesOptions = [
            'Creche', 'Maternelle', 'Primaire', 'Collège', 'Lycée', 'Supérieur',
            'Very Young Learners', 'Kids', 'Pre-teens', 'Teens', 'Adults'
        ];
        return view('contacts.edit', compact('contact', 'villes', 'categoriesOptions', 'cyclesOptions'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:contacts,email,' . $contact->id,
            'telephone' => 'nullable|string|max:20',
            'ville_id' => 'required|exists:villes,id',
            'civilite' => 'nullable|in:M.,Mme,Mlle',
            'fonction' => 'nullable|in:Directeur,Responsable,Enseignant,Autre',
            'categories' => 'nullable|array',
            'categories.*' => 'string',
            'cycles' => 'nullable|array',
            'cycles.*' => 'string',
        ]);

        $contact->update(array_merge(
            $validated,
            [
                'categories' => $validated['categories'] ?? [],
                'cycles' => $validated['cycles'] ?? [],
            ]
        ));

        return redirect()->route('contacts.index')
            ->with('success', 'Contact mis à jour.');
    }

    public function destroy(Contact $contact)
    {
        // Check if contact is linked to any compte before deletion
        if ($contact->comptes()->exists()) {
            return redirect()->route('contacts.index')
                ->with('error', 'Impossible de supprimer ce contact car il est associé à des comptes.');
        }

        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact supprimé.');
    }


    // New methods
    private function getRboDelegueIds(User $rbo): Collection
    {
        return $rbo->zonesAsRbo()
            ->with('delegates')
            ->get()
            ->flatMap(fn ($zone) => $zone->delegates->pluck('id'))
            ->unique();
    }
    public function getComptes(Contact $contact)
{
    $user = auth()->user();
    // Get comptes that the user is allowed to see (based on role)
    $query = Compte::with('ville')->orderBy('etablissement');
    
    if ($user->role === 'delegue') {
        $query->where('delegue_id', $user->id);
    } elseif ($user->role === 'rbo') {
        $delegateIds = $this->getRboDelegueIds($user);
        $query->whereIn('delegue_id', $delegateIds);
    }
    // Admin sees all
    
    // IMPORTANT: Filter by the same ville as the contact
    $query->where('ville_id', $contact->ville_id);
    
    $allComptes = $query->get();
    $assignedIds = $contact->comptes->pluck('id')->toArray();
    
    $comptesList = $allComptes->map(function ($compte) use ($assignedIds) {
        return [
            'id' => $compte->id,
            'name' => $compte->etablissement . ' (' . ($compte->ville->nom ?? '') . ')',
            'assigned' => in_array($compte->id, $assignedIds)
        ];
    });
    
    return response()->json([
        'all_comptes' => $comptesList->values(),
        'assigned_ids' => $assignedIds
    ]);
}

public function updateComptes(Request $request, Contact $contact)
{
    $request->validate([
        'compte_ids' => 'array',
        'compte_ids.*' => 'exists:comptes,id'
    ]);

    $newCompteIds = $request->compte_ids ?? [];

    // Current active assignments
    $currentAssignments = CompteContact::where('contact_id', $contact->id)
        ->whereNull('date_fin')
        ->get();

    $currentCompteIds = $currentAssignments->pluck('compte_id')->toArray();

    /*
    |--------------------------------------------------------------------------
    | CLOSE REMOVED ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    $removedCompteIds = array_diff($currentCompteIds, $newCompteIds);

    CompteContact::where('contact_id', $contact->id)
        ->whereIn('compte_id', $removedCompteIds)
        ->whereNull('date_fin')
        ->update([
            'date_fin' => now()
        ]);

    /*
    |--------------------------------------------------------------------------
    | CREATE NEW ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    $addedCompteIds = array_diff($newCompteIds, $currentCompteIds);

    foreach ($addedCompteIds as $compteId) {

        CompteContact::create([
            'contact_id' => $contact->id,
            'compte_id' => $compteId,
            'date_debut' => now(),
            'date_fin' => null,
        ]);
    }

    return response()->json([
        'success' => true
    ]);
}


// helper methdes  
        private function getAvailableComptes()
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
            return collect(); // fallback
        }
}