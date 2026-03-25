<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use App\Models\Quartier;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function index(Request $request)
{
    $query = Compte::with(['ville', 'zone', 'delegue']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('etablissement', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhereHas('ville', fn($q) => $q->where('nom', 'like', "%{$search}%"))
              ->orWhereHas('zone', fn($q) => $q->where('name', 'like', "%{$search}%"));
        });
    }

    $comptes = $query->paginate(15);
    return view('comptes.index', compact('comptes'));
}
    public function create()
    {
        $villes = Ville::all();
        $zones = Zone::all();
        $delegues = User::where('role', 'delegue')->get();
        $quartiers = Quartier::with('zone')->get(); // for all quartiers
        $types = ['ecole', 'centre_de_langue', 'librairie', 'autre'];
        $cycles = ['Maternelle', 'Primaire', 'Collège', 'Lycée', 'Kids', 'Teens', 'Adults'];
        $statuses = ['actif', 'ferme'];

        return view('comptes.create', compact(
            'villes', 'zones', 'delegues','quartiers', 'types', 'cycles', 'statuses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:ecole,centre_de_langue,librairie,autre',
            'etablissement' => 'required|string|max:255',
            'ville_id' => 'required|exists:villes,id',
            'zone_id' => 'required|exists:zones,id',
            'quartier_id' => 'nullable|exists:quartiers,id',
            'adresse' => 'required|string',
            'delegue_id' => 'required|exists:users,id',
            'status' => 'required|in:actif,ferme',
            'motif_fermeture' => 'nullable|required_if:status,ferme|string',
            'cycle' => 'nullable|in:Maternelle,Primaire,Collège,Lycée,Kids,Teens,Adults',
            'tel_bureau_1' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        // Additional validation: ensure the delegate belongs to the zone
        $zone = Zone::with('delegates')->find($validated['zone_id']);
        if (!$zone->delegates->contains('id', $validated['delegue_id'])) {
            return back()->withErrors(['delegue_id' => 'Le délégué sélectionné n\'est pas assigné à cette zone.'])->withInput();
        }

        Compte::create($validated);

        return redirect()->route('comptes.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function edit(Compte $compte)
    {
        $villes = Ville::all();
        $zones = Zone::all();
        $delegues = User::where('role', 'delegue')->get();
        $types = ['ecole', 'centre_de_langue', 'librairie', 'autre'];
        $quartiers = Quartier::with('zone')->get(); // for all quartiers
        $cycles = ['Maternelle', 'Primaire', 'Collège', 'Lycée', 'Kids', 'Teens', 'Adults'];
        $statuses = ['actif', 'ferme'];

        return view('comptes.edit', compact(
            'compte', 'villes', 'zones', 'delegues', 'types', 'cycles', 'statuses' , 'quartiers'
        ));
    }

    public function update(Request $request, Compte $compte)
    {
        $validated = $request->validate([
            'type' => 'required|in:ecole,centre_de_langue,librairie,autre',
            'etablissement' => 'required|string|max:255',
            'ville_id' => 'required|exists:villes,id',
            'zone_id' => 'required|exists:zones,id',
            'adresse' => 'required|string',
            'delegue_id' => 'required|exists:users,id',
            'status' => 'required|in:actif,ferme',
            'motif_fermeture' => 'nullable|required_if:status,ferme|string',
            'cycle' => 'nullable|in:Maternelle,Primaire,Collège,Lycée,Kids,Teens,Adults',
            'tel_bureau_1' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        // Ensure delegate belongs to zone
        $zone = Zone::with('delegates')->find($validated['zone_id']);
        if (!$zone->delegates->contains('id', $validated['delegue_id'])) {
            return back()->withErrors(['delegue_id' => 'Le délégué sélectionné n\'est pas assigné à cette zone.'])->withInput();
        }

        $compte->update($validated);

        return redirect()->route('comptes.index')
            ->with('success', 'Compte mis à jour.');
    }

    public function destroy(Compte $compte)
    {
        // Check for related records? For now, just delete (cascade not set)
        $compte->delete();

        return redirect()->route('comptes.index')
            ->with('success', 'Compte supprimé.');
    }
}