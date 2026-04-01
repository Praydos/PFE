<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Ville;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('ville')->paginate(15);
        return view('contacts.index', compact('contacts'));
    }

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
}