<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\User;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TacheController extends Controller
{
    private function authorizeView(Tache $tache)
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if ($user->role === 'delegue' && $tache->delegue_id === $user->id)
            return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($tache->delegue_id))
                return;
        }
        dd($user->role, $tache->lieu, $user->id); // temporary
        abort(403);
    }

    private function authorizeEdit(Tache $tache)
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if ($user->role === 'delegue' && $tache->delegue_id === $user->id)
            return;
        abort(403);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Tache::with('delegate');

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        }
        elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'validated')
                $query->where('is_validated', true);
            else
                $query->where('is_validated', false);
        }

        $taches = $query->orderBy('date_planification', 'desc')->paginate(15);
        return view('taches.index', compact('taches'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue')
            abort(403);
        $contacts = Contact::whereHas('comptes', fn($q) => $q->where('delegue_id', $user->id))->get();
        $defaultDate = $request->get('date_planification', now()->toDateString());
        return view('taches.create', compact('contacts', 'defaultDate'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue')
            abort(403);

        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_planification' => 'required|date',
            'heure' => 'nullable|date_format:H:i',
            'lieu' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*' => 'exists:contacts,id',
            'all_day' => 'nullable|boolean',
            'date_fin' => 'nullable|date|after_or_equal:date_planification',
            'recurrence_frequence' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_intervalle' => 'nullable|integer|min:1',
            'recurrence_fin' => 'nullable|date|after_or_equal:date_planification',
        ]);

        $validated['delegue_id'] = $user->id;
        $validated['contacts'] = $validated['contacts'] ?? [];
        $validated['all_day'] = $request->has('all_day');
        $validated['is_validated'] = false;

        // Create parent task
        $parent = Tache::create($validated);

        // Generate recurrence
        if (!empty($validated['recurrence_frequence']) && !empty($validated['recurrence_fin'])) {
            $occurrences = $this->generateRecurrence($validated);
            // Remove the first occurrence (already created as parent)
            $occurrences = array_values(array_filter($occurrences, fn($d) => $d != $validated['date_planification']));
            for ($i = 0; $i < count($occurrences); $i++) {
                $date = $occurrences[$i];
                $childData = [
                    'objet' => $validated['objet'],
                    'description' => $validated['description'] ?? null,
                    'date_planification' => $date,
                    'heure' => $validated['heure'] ?? null,
                    'lieu' => $validated['lieu'] ?? null,
                    'contacts' => $validated['contacts'],
                    'all_day' => $validated['all_day'],
                    'date_fin' => $validated['date_fin'] ?? null,
                    'delegue_id' => $user->id,
                    'parent_tache_id' => $parent->id,
                    'is_validated' => false,
                ];
                Tache::create($childData);
            }
        }

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

        // Delete existing children (if any)
        $tache->children()->delete();

        $tache->update($validated);

        // Regenerate children
        $occurrences = $this->generateRecurrence($validated);
        foreach ($occurrences as $date) {
            if ($date == $validated['date_planification'])
                continue;
            $childData = $validated;
            $childData['date_planification'] = $date;
            $childData['parent_tache_id'] = $tache->id;
            $childData['recurrence_frequence'] = null;
            Tache::create($childData);
        }
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
        if (!in_array($user->role, ['admin', 'rbo']))
            abort(403);
        if ($tache->is_validated) {
            return redirect()->back()->with('error', 'Déjà validée.');
        }
        $tache->update([
            'is_validated' => true,
            'date_validation' => now(),
        ]);
        return redirect()->route('taches.index')->with('success', 'Tâche validée.');
    }


    // Fix the interval — wrap in parentheses so ?? fires correctly
    private function generateRecurrence($data)
    {
        $start = Carbon::parse($data['date_planification']);
        $end = $data['recurrence_fin'] ?Carbon::parse($data['recurrence_fin']) : $start;
        $freq = $data['recurrence_frequence'] ?? null;
        $interval = (int)($data['recurrence_intervalle'] ?? 1);
        if ($interval < 1)
            $interval = 1; // ← parentheses fixed

        if (!$freq || $interval < 1)
            return []; // ← also guard interval < 1

        $unitMap = [
            'daily' => 'days',
            'weekly' => 'weeks',
            'monthly' => 'months',
            'yearly' => 'years',
        ];
        $unit = $unitMap[$freq];

        $dates = [];
        $current = $start->copy();
        while ($current <= $end) {
            $dates[] = $current->toDateString();
            $current->add($interval, $unit);
        }
        return $dates;
    }


    public function cancelRecurrence(Request $request, Tache $tache)
    {
        $this->authorizeEdit($tache);

        $scope = $request->input('scope', 'this_and_following');

        // Resolve the root parent of the series
        $parent = $tache->parent_tache_id
            ?Tache::findOrFail($tache->parent_tache_id)
            : $tache;

        switch ($scope) {

            case 'this_and_following':
                // Delete this child + all siblings on or after this date
                $parent->children()
                    ->where('date_planification', '>=', $tache->date_planification->toDateString())
                    ->delete();

                // If the task IS the parent, also clear its own recurrence fields
                if (!$tache->parent_tache_id) {
                    $parent->update([
                        'recurrence_frequence' => null,
                        'recurrence_intervalle' => null,
                        'recurrence_fin' => null,
                    ]);
                    return redirect()->route('taches.show', $parent)
                        ->with('success', 'Récurrences futures supprimées.');
                }

                // If it was a child, delete the child itself too
                $tache->delete();

                // Shorten the parent's recurrence_fin to the day before this task
                $parent->update([
                    'recurrence_fin' => $tache->date_planification->subDay()->toDateString(),
                ]);

                return redirect()->route('taches.show', $parent)
                    ->with('success', 'Cette occurrence et les suivantes ont été supprimées.');

            case 'all':
                // Wipe every child in the series
                $parent->children()->delete();

                // Clear recurrence on the parent so it becomes a one-off task
                $parent->update([
                    'recurrence_frequence' => null,
                    'recurrence_intervalle' => null,
                    'recurrence_fin' => null,
                ]);

                return redirect()->route('taches.show', $parent)
                    ->with('success', 'Toute la série de récurrence a été supprimée.');
        }

        return redirect()->route('taches.show', $tache);
    }

    // ── For-delegate flow (RBO / Admin) ──────────────────────────────────

    public function createForDelegate(Request $request, User $delegate)
    {
        $this->authorizeForDelegate($delegate);

        $contacts = Contact::whereHas('comptes', fn($q) => $q->where('delegue_id', $delegate->id))->get();
        $defaultDate = $request->get('date_planification', now()->toDateString());
        $targetDelegate = $delegate;

        return view('taches.create', compact('contacts', 'defaultDate', 'targetDelegate'));
    }

    public function storeForDelegate(Request $request, User $delegate)
    {
        $this->authorizeForDelegate($delegate);

        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_planification' => 'required|date',
            'heure' => 'nullable|date_format:H:i',
            'lieu' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*' => 'exists:contacts,id',
            'all_day' => 'nullable|boolean',
            'date_fin' => 'nullable|date|after_or_equal:date_planification',
            'recurrence_frequence' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_intervalle' => 'nullable|integer|min:1',
            'recurrence_fin' => 'nullable|date|after_or_equal:date_planification',
        ]);

        $validated['delegue_id'] = $delegate->id;
        $validated['contacts'] = $validated['contacts'] ?? [];
        $validated['all_day'] = $request->has('all_day');
        $validated['is_validated'] = false;

        $parent = Tache::create($validated);

        if (!empty($validated['recurrence_frequence']) && !empty($validated['recurrence_fin'])) {
            $occurrences = $this->generateRecurrence($validated);
            $occurrences = array_values(array_filter($occurrences, fn($d) => $d != $validated['date_planification']));
            foreach ($occurrences as $date) {
                Tache::create([
                    'objet' => $validated['objet'],
                    'description' => $validated['description'] ?? null,
                    'date_planification' => $date,
                    'heure' => $validated['heure'] ?? null,
                    'lieu' => $validated['lieu'] ?? null,
                    'contacts' => $validated['contacts'],
                    'all_day' => $validated['all_day'],
                    'date_fin' => $validated['date_fin'] ?? null,
                    'delegue_id' => $delegate->id,
                    'parent_tache_id' => $parent->id,
                    'is_validated' => false,
                ]);
            }
        }

        return redirect()->route('taches.index')
            ->with('success', 'Tâche créée pour ' . $delegate->prenom . ' ' . $delegate->nom . '.');
    }

    private function authorizeForDelegate(User $delegate): void
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($delegate->id))
                return;
        }
        abort(403, 'Non autorisé à créer des tâches pour ce délégué.');
    }
}