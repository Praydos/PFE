<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contact;
use App\Models\Compte;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\AnneeScolaire;
use App\Models\User;
use App\Support\YearLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    private function getCurrentYear()
    {
        return AnneeScolaire::where('is_active', true)->first() ?? AnneeScolaire::latest('date_debut')->first();
    }

    // Helper: get villes accessible to the current delegate (via zones)
    private function getDelegateVilles()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return Ville::orderBy('nom')->get();
        }
        if ($user->role === 'delegue') {
            $zoneIds = $user->zones->pluck('id');
            return Ville::whereHas('zones', fn($q) => $q->whereIn('zones.id', $zoneIds))->get();
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $zoneIds = Zone::whereIn('rbo_id', [$user->id])->orWhereHas('delegates', fn($q) => $q->whereIn('users.id', $delegateIds))->pluck('id');
            return Ville::whereHas('zones', fn($q) => $q->whereIn('zones.id', $zoneIds))->get();
        }
        return collect();
    }

    // Index: list events with role-based scoping
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Event::with(['ville', 'zone', 'delegate', 'anneeScolaire']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        }
        elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }

        if ($request->filled('type'))
            $query->where('type', $request->type);
        if ($request->filled('editeur'))
            $query->where('editeur', $request->editeur);
        if ($request->filled('ville_id'))
            $query->where('ville_id', $request->ville_id);

        $events = $query->orderBy('date_event', 'desc')->paginate(15);
        $villes = $this->getDelegateVilles();
        $types = ['Public Speaking', 'Ateliers de lecture', 'English Day', 'Compétitions', 'Amizing minds', 'Workshop', 'Exposition de livres', 'Salon', 'Formation Editeur', 'Présentation Produit'];
        $editeurs = ['Esprit du livre', 'Matifica', 'Express publishing', 'Bookland'];

        return view('events.index', compact('events', 'villes', 'types', 'editeurs'));
    }

    // Create form
    public function create(Request $request)    {
        $user = Auth::user();
        if ($user->role !== 'delegue')
            abort(403);

        $villes = $this->getDelegateVilles();
        $currentYear = $this->getCurrentYear();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $types = ['Public Speaking', 'Ateliers de lecture', 'English Day', 'Compétitions', 'Amizing minds', 'Workshop', 'Exposition de livres', 'Salon', 'Formation Editeur', 'Présentation Produit'];
        $editeurs = ['Esprit du livre', 'Matifica', 'Express publishing', 'Bookland'];

        // Pre‑fill ville and zone if a compte_id is provided
        $selectedCompteId = request('compte_id');
        $defaultVilleId = null;
        $defaultZoneId = null;
        if ($selectedCompteId) {
            $compte = Compte::find($selectedCompteId);
            if ($compte && $compte->delegue_id == $user->id) {
                $defaultVilleId = $compte->ville_id;
                $defaultZoneId = $compte->zone_id;
            }
        }
        $defaultDate = $request->get('date_event', now()->toDateString());

        return view('events.create', compact('villes', 'currentYear', 'years', 'types', 'editeurs', 'defaultVilleId', 'defaultZoneId', 'defaultDate'));    }

    // Store event
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue')
            abort(403);

        $validated = $request->validate([
            'ville_id' => 'required|exists:villes,id',
            'type' => 'required|string',
            'editeur' => 'required|string',
            'date_event' => 'required|date',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
        ]);

        // Get zone from ville (using the first zone of the ville – adjust if multiple zones)
        $ville = Ville::find($validated['ville_id']);
        $zone = $ville->zones()->first();
        if (!$zone) {
            return redirect()->back()->withErrors(['ville_id' => 'Cette ville n\'a pas de zone associée.']);
        }

        $validated['zone_id'] = $zone->id;
        $validated['delegue_id'] = $user->id;

        $event = Event::create($validated);

        // After creating, redirect to invite contacts page
        return redirect()->route('events.invite', $event)->with('success', 'Événement créé. Vous pouvez maintenant inviter des contacts.');
    }

    // Invite contacts form (two methods: by city or direct selection)
    public function inviteForm(Event $event)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' || $event->delegue_id !== $user->id)
            abort(403);

        $villes = $this->getDelegateVilles();
        // Get already invited contacts
        $invitedIds = $event->contacts->pluck('id')->toArray();

        return view('events.invite', compact('event', 'villes', 'invitedIds'));
    }

    // API: get contacts by city (for the "by city" method)
    public function getContactsByCity(Request $request)    {
        $user = Auth::user();
        if ($user->role !== 'delegue') {
            return response()->json([]);
        }

        $villeIds = $request->input('ville_ids');
        if (!$villeIds)
            return response()->json([]);

        // Handle both array and comma-separated string
        if (is_string($villeIds)) {
            $villeIds = array_filter(explode(',', $villeIds));
        }

        if (empty($villeIds))
            return response()->json([]);

        $assignedContactIds = \DB::table('compte_contact')
            ->join('comptes', 'compte_contact.compte_id', '=', 'comptes.id')
            ->where('comptes.delegue_id', $user->id)
            ->pluck('compte_contact.contact_id')
            ->unique();

        $contacts = Contact::whereIn('id', $assignedContactIds)
            ->whereIn('ville_id', $villeIds)
            ->with('ville')
            ->get()
            ->map(fn($c) => [
        'id' => $c->id,
        'name' => $c->prenom . ' ' . $c->nom,
        'ville' => $c->ville->nom,
        'fonction' => $c->fonction,
        ]);

        return response()->json($contacts);    }

    // API: get all contacts for direct selection (filtered by delegate's villes)
    public function getAllContacts()    {
        $user = Auth::user();
        // Only delegates can access this endpoint
        if ($user->role !== 'delegue') {
            return response()->json([]);
        }

        // Get contact IDs that are assigned to the delegate through comptes
        $contactIds = \DB::table('compte_contact')
            ->join('comptes', 'compte_contact.compte_id', '=', 'comptes.id')
            ->where('comptes.delegue_id', $user->id)
            ->pluck('compte_contact.contact_id')
            ->unique();

        $contacts = Contact::whereIn('id', $contactIds)
            ->with('ville')
            ->get()
            ->map(fn($c) => [
        'id' => $c->id,
        'name' => $c->prenom . ' ' . $c->nom,
        'ville' => $c->ville->nom,
        'fonction' => $c->fonction,
        ]);

        return response()->json($contacts);    }

    // Store invitations (sync contacts)
    public function storeInvitations(Request $request, Event $event)    {
        $user = Auth::user();
        if ($user->role !== 'delegue' || $event->delegue_id !== $user->id)
            abort(403);

        $contactIdsRaw = $request->input('contact_ids');
        if (is_string($contactIdsRaw)) {
            $contactIds = array_filter(explode(',', $contactIdsRaw));
        }
        else {
            $contactIds = $contactIdsRaw ?? [];
        }

        // Override the request input with the array
        $request->merge(['contact_ids' => $contactIds]);

        $validated = $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id',
        ]);

        $syncData = [];
        foreach ($validated['contact_ids'] as $contactId) {
            $syncData[$contactId] = ['statut' => 'invite'];
        }
        $event->contacts()->syncWithoutDetaching($syncData);

        return redirect()->route('events.show', $event)->with('success', 'Invitations envoyées.');    }
    // Show event details with contacts and their statuses
    public function show(Event $event)
    {
        $this->authorizeView($event);
        $event->load('contacts', 'ville', 'zone', 'delegate', 'anneeScolaire');
        $statuts = ['invite' => 'Invité', 'accepte' => 'Accepté', 'decline' => 'Décliné', 'present' => 'Présent'];
        return view('events.show', compact('event', 'statuts'));
    }

    // Update contact status (from show view)
    public function updateStatus(Request $request, Event $event, Contact $contact)
    {
        YearLock::check($event);
        $user = Auth::user();
        if ($user->role !== 'delegue' || $event->delegue_id !== $user->id)
            abort(403);

        $validated = $request->validate([
            'statut' => 'required|in:invite,accepte,decline,present',
        ]);

        $event->contacts()->updateExistingPivot($contact->id, ['statut' => $validated['statut']]);

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    // Edit event (only basic info, not contacts)
    public function edit(Event $event)
    {

        $this->authorizeEdit($event);
        $villes = $this->getDelegateVilles();
        $years = AnneeScolaire::orderBy('date_debut', 'desc')->get();
        $types = ['Public Speaking', 'Ateliers de lecture', 'English Day', 'Compétitions', 'Amizing minds', 'Workshop', 'Exposition de livres', 'Salon', 'Formation Editeur', 'Présentation Produit'];
        $editeurs = ['Esprit du livre', 'Matifica', 'Express publishing', 'Bookland'];
        $currentYear = $this->getCurrentYear();
        return view('events.edit', compact('event', 'villes', 'years', 'types', 'editeurs', 'currentYear'));
    }

    public function update(Request $request, Event $event)
    {
        YearLock::check($event);
        $this->authorizeEdit($event);
        $validated = $request->validate([
            'ville_id' => 'required|exists:villes,id',
            'type' => 'required|string',
            'editeur' => 'required|string',
            'date_event' => 'required|date',
            'annee_scolaire_id' => 'required|exists:annees_scolaires,id',
        ]);
        $ville = Ville::find($validated['ville_id']);
        $zone = $ville->zones()->first();
        if (!$zone) {
            return redirect()->back()->withErrors(['ville_id' => 'Cette ville n\'a pas de zone associée.']);
        }
        $validated['zone_id'] = $zone->id;
        $event->update($validated);
        return redirect()->route('events.show', $event)->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $event)
    {
        YearLock::check($event);
        $this->authorizeEdit($event);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Événement supprimé.');
    }

    // Reporting (statistics)
    public function statistics(Event $event)
    {
        $this->authorizeView($event);
        $stats = [
            'total_invites' => $event->contacts->count(),
            'total_presents' => $event->contacts->where('pivot.statut', 'present')->count(),
            'total_acceptes' => $event->contacts->where('pivot.statut', 'accepte')->count(),
            'total_declines' => $event->contacts->where('pivot.statut', 'decline')->count(),
            'participation_rate' => $event->contacts->count() > 0 ? round(($event->contacts->where('pivot.statut', 'present')->count() / $event->contacts->count()) * 100, 2) : 0,
            'by_ville' => $event->contacts->groupBy('ville.nom')->map(function ($group) {
            return [
            'total' => $group->count(),
            'presents' => $group->where('pivot.statut', 'present')->count(),
            'rate' => $group->count() > 0 ? round(($group->where('pivot.statut', 'present')->count() / $group->count()) * 100, 2) : 0,
            ];
        }),
            'by_delegate' => [], // If multiple delegates, but event has one delegate
        ];
        return view('events.statistics', compact('event', 'stats'));
    }

    private function authorizeView(Event $event)
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if ($user->role === 'delegue' && $event->delegue_id === $user->id)
            return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($event->delegue_id))
                return;
        }
        abort(403);
    }

    private function authorizeEdit(Event $event)
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if ($user->role === 'delegue' && $event->delegue_id === $user->id)
            return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($event->delegue_id))
                return;
        }
        abort(403);
    }
}