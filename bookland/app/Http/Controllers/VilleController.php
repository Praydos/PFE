<?php

namespace App\Http\Controllers;

use App\Models\Ville;
use App\Models\Zone;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    public function index(Request $request) // <-- Inject Request
    {
        $search = $request->get('search');

        $villes = Ville::withCount('zones')
            ->when($search, function ($query, $search) {
                return $query->where('nom', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('villes.index', compact('villes'));
    }

    public function create()
    {
        return view('villes.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:villes,nom',
        ]);

        Ville::create($validated);

        return redirect()->route('villes.index')
            ->with('success', 'Ville créée avec succès.');
    }

    public function edit(Ville $ville)
    {
        $assignedZones = $ville->zones;
        $availableZones = Zone::where('ville_id', '!=', $ville->id)->with('ville')->get();

        return view('villes.edit', compact('ville', 'assignedZones', 'availableZones'));
    }

    public function update(Request $request, Ville $ville)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:villes,nom,' . $ville->id,
        ]);

        $ville->update($validated);

        return redirect()->route('villes.index')
            ->with('success', 'Ville mise à jour.');
    }

    public function destroy(Ville $ville)
    {
        // Check if ville has zones
        if ($ville->zones()->count() > 0) {
            return redirect()->route('villes.index')
                ->with('error', 'Impossible de supprimer une ville qui a des zones.');
        }

        $ville->delete();
        return redirect()->route('villes.index')
            ->with('success', 'Ville supprimée.');
    }

    public function assignZone(Request $request, Ville $ville)
{
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
        ]);

        $zone = Zone::findOrFail($request->zone_id);

        // Optional: prevent assigning a zone that already belongs to this city
        if ($zone->ville_id == $ville->id) {
            return redirect()->back()->with('error', 'Cette zone est déjà dans cette ville.');
        }

        $zone->update(['ville_id' => $ville->id]);

    return redirect()->back()->with('success', 'Zone assignée à la ville.');
}

    public function getRbos(Ville $ville)
    {
        return response()->json($ville->rbos);
    }
}