<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AnneScolaireController extends Controller
{
    public function index()
    {
        $annees = AnneeScolaire::orderBy('date_debut', 'desc')->paginate(10);
        return view('annees_scolaires.index', compact('annees'));
    }

    public function create()
    {
        return view('annees_scolaires.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:20|unique:annees_scolaires,libelle',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        // If no active year exists, set this one as active by default
        $activeExists = AnneeScolaire::where('is_active', true)->exists();
        $validated['is_active'] = !$activeExists;
        $validated['is_closed'] = false;

        AnneeScolaire::create($validated);

        return redirect()->route('annees-scolaires.index')
            ->with('success', 'Année scolaire créée avec succès.');
    }

    public function edit(AnneeScolaire $annees_scolaire)
    {
        return view('annees_scolaires.edit', compact('annees_scolaire'));
    }

    public function update(Request $request, AnneeScolaire $annees_scolaire)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:20|unique:annees_scolaires,libelle,' . $annees_scolaire->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        // Prevent modification if year is closed
        if ($annees_scolaire->is_closed) {
            return redirect()->back()->withErrors(['error' => 'Cette année est verrouillée.']);
        }

        $annees_scolaire->update($validated);

        return redirect()->route('annees-scolaires.index')
            ->with('success', 'Année scolaire mise à jour.');
    }

    public function destroy(AnneeScolaire $annees_scolaire)
    {
        // Prevent deletion if it's the active year or has linked data (specimens, adoptions)
        if ($annees_scolaire->is_active) {
            return redirect()->back()->with('error', 'Impossible de supprimer l\'année active.');
        }
        // TODO: add check for related records (specimens, adoptions) later

        $annees_scolaire->delete();
        return redirect()->route('annees-scolaires.index')
            ->with('success', 'Année scolaire supprimée.');
    }

    // Custom method: set a year as active
    public function setActive(AnneeScolaire $annees_scolaire)
    {
        if ($annees_scolaire->is_closed) {
            return redirect()->back()->with('error', 'Impossible d\'activer une année fermée.');
        }
        $annees_scolaire->setActive();
        return redirect()->route('annees-scolaires.index')
            ->with('success', "L'année {$annees_scolaire->libelle} est maintenant active.");
    }

    // Custom method: close a year (lock data)
    public function close(AnneeScolaire $annees_scolaire)
    {
        if ($annees_scolaire->is_active) {
            return redirect()->back()->with('error', "Fermez d'abord l'année en créant une nouvelle année active.");
        }
        $annees_scolaire->close();
        return redirect()->route('annees-scolaires.index')
            ->with('success', "L'année {$annees_scolaire->libelle} a été fermée.");
    }
}