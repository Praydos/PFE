<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Ville;
use App\Models\User;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::with(['ville', 'rbo'])->paginate(10);
        return view('zones.index', compact('zones'));
    }

    public function create()
    {   // fetch all villes, rbo users and delegue users to populate the form dropdowns
        $villes = Ville::all();
        $rbos = User::where('role', 'rbo')->get();
        $delegues = User::where('role', 'delegue')->get();
        // Pass an empty zone instance to avoid errors, or just use the conditional checks
        $zone = null; // or new Zone();
        $selectedVilleId = request('ville_id');
        return view('zones.create', compact('villes', 'rbos', 'delegues', 'zone','selectedVilleId'));
    }

    public function store(Request $request)
    {   
        //dd($request->all()); // ← temporary debug
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:zones,name',
            'ville_id' => 'required|exists:villes,id',
            'rbo_id' => 'required|exists:users,id',
            'delegues' => 'array',
            'delegues.*' => 'exists:users,id',
        ]);
        // Create the zone
        $zone = Zone::create([
            'name' => $validated['name'],
            'ville_id' => $validated['ville_id'],
            'rbo_id' => $validated['rbo_id'],
        ]);

        if (isset($validated['delegues'])) {
            $zone->delegates()->sync($validated['delegues']);
        }

        return redirect()->route('zones.index')
            ->with('success', 'Zone créée avec succès.');
    }

    public function edit(Zone $zone)
    {
        $villes = Ville::all();
        $rbos = User::where('role', 'rbo')->get();
        $delegues = User::where('role', 'delegue')->get();
        $zone->load('delegates');
        return view('zones.edit', compact('zone', 'villes', 'rbos', 'delegues'));
    }

    public function update(Request $request, Zone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:zones,name,' . $zone->id,
            'ville_id' => 'required|exists:villes,id',
            'rbo_id' => 'required|exists:users,id',
            'delegues' => 'array',
            'delegues.*' => 'exists:users,id',
        ]);

        $zone->update([
            'name' => $validated['name'],
            'ville_id' => $validated['ville_id'],
            'rbo_id' => $validated['rbo_id'],
        ]);

        $zone->delegates()->sync($validated['delegues'] ?? []);

        return redirect()->route('zones.index')
            ->with('success', 'Zone mise à jour.');
    }

    public function destroy(Zone $zone)
    {
        // Check if zone has comptes
        if ($zone->comptes()->count() > 0) {
            return redirect()->route('zones.index')
                ->with('error', 'Impossible de supprimer une zone qui a des comptes.');
        }

        $zone->delegates()->detach(); // remove pivot entries
        $zone->delete();

        return redirect()->route('zones.index')
            ->with('success', 'Zone supprimée.');
    }
}