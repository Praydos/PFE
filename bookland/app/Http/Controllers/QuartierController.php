<?php

namespace App\Http\Controllers;

use App\Models\Quartier;
use App\Models\Zone;
use Illuminate\Http\Request;

class QuartierController extends Controller
{
    public function index()
    {
        $quartiers = Quartier::with('zone')->paginate(15);
        return view('quartiers.index', compact('quartiers'));
    }

    public function create()
    {
        $zones = Zone::with('ville')->get();
        return view('quartiers.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,id',
        ]);

        Quartier::create($validated);

        return redirect()->route('quartiers.index')
            ->with('success', 'Quartier créé avec succès.');
    }

    public function edit(Quartier $quartier)
    {
        $zones = Zone::with('ville')->get();
        return view('quartiers.edit', compact('quartier', 'zones'));
    }

    public function update(Request $request, Quartier $quartier)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,id',
        ]);

        $quartier->update($validated);

        return redirect()->route('quartiers.index')
            ->with('success', 'Quartier mis à jour.');
    }

    public function destroy(Quartier $quartier)
    {
        // Check if quartier has comptes before deletion
        if ($quartier->comptes()->exists()) {
            return redirect()->route('quartiers.index')
                ->with('error', 'Impossible de supprimer ce quartier car il est associé à des comptes.');
        }

        $quartier->delete();

        return redirect()->route('quartiers.index')
            ->with('success', 'Quartier supprimé.');
    }
}