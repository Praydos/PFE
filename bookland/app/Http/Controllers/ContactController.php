<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Compte;
use App\Models\Ville;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Collection;

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
    $user = auth()->user();
    
    $request->validate([
        'compte_ids' => 'array',
        'compte_ids.*' => 'exists:comptes,id'
    ]);
    
    // Get the selected comptes and check their ville_id
    $selectedComptes = Compte::whereIn('id', $request->compte_ids ?? [])->get();
    foreach ($selectedComptes as $compte) {
        if ($compte->ville_id !== $contact->ville_id) {
            return response()->json([
                'error' => "Le compte {$compte->etablissement} n'appartient pas à la même ville que le contact."
            ], 422);
        }
    }
    
    $contact->comptes()->sync($request->compte_ids ?? []);
    
    return response()->json(['success' => true]);
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