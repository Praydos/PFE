<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TacheController extends Controller
{
    private function authorizeView(Tache $tache)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $tache->delegue_id === $user->id) return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($tache->delegue_id)) return;
        }
        dd($user->role, $tache->lieu, $user->id); // temporary
        abort(403);
    }

    private function authorizeEdit(Tache $tache)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delegue' && $tache->delegue_id === $user->id) return;
        abort(403);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Tache::with('delegate');

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'validated') $query->where('is_validated', true);
            else $query->where('is_validated', false);
        }

        $taches = $query->orderBy('date_planification', 'desc')->paginate(15);
        return view('taches.index', compact('taches'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);
        $contacts = Contact::whereHas('comptes', fn($q) => $q->where('delegue_id', $user->id))->get();
        return view('taches.create', compact('contacts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue') abort(403);

        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_planification' => 'required|date',
            'lieu' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*' => 'exists:contacts,id',
            'all_day' => 'nullable|boolean',
            'date_fin' => 'nullable|date|after_or_equal:date_planification',
        ]);

        $validated['delegue_id'] = $user->id;
        $validated['contacts'] = $validated['contacts'] ?? [];
        $validated['all_day'] = $request->has('all_day');
        $validated['is_validated'] = false;

        Tache::create($validated);

        return redirect()->route('taches.index')->with('success', 'Tâche créée.');
    }

    public function show(Tache $tache)
    {
       
        $this->authorizeView($tache);
        return view('taches.show', compact('tache'));
    }

    public function edit(Tache $tache)
    {
        $this->authorizeEdit($tache);
        $user = Auth::user();
        $contacts = Contact::whereHas('comptes', fn($q) => $q->where('delegue_id', $user->id))->get();
        return view('taches.edit', compact('tache', 'contacts'));
    }

    public function update(Request $request, Tache $tache)
    {
        $this->authorizeEdit($tache);
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_planification' => 'required|date',
            'lieu' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*' => 'exists:contacts,id',
            'all_day' => 'nullable|boolean',
            'date_fin' => 'nullable|date|after_or_equal:date_planification',
        ]);
        $validated['contacts'] = $validated['contacts'] ?? [];
        $validated['all_day'] = $request->has('all_day');
        $tache->update($validated);
        return redirect()->route('taches.index')->with('success', 'Tâche mise à jour.');
    }

    public function destroy(Tache $tache)
    {
        $this->authorizeEdit($tache);
        $tache->delete();
        return redirect()->route('taches.index')->with('success', 'Tâche supprimée.');
    }

    // Validation by RBO/Admin
    public function validateTache(Tache $tache)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) abort(403);
        if ($tache->is_validated) {
            return redirect()->back()->with('error', 'Déjà validée.');
        }
        $tache->update([
            'is_validated' => true,
            'date_validation' => now(),
        ]);
        return redirect()->route('taches.index')->with('success', 'Tâche validée.');
    }
}