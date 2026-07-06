<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = Event::with(['ville', 'contacts']);

        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $query->whereIn('delegue_id', $delegueIds);
        }
        return $query;
    }

    public function index(Request $request)
    {
        return response()->json($this->scopedQuery()->latest()->paginate(50));
    }

    public function show(Event $event)
    {
        $event->load(['ville', 'contacts']);
        return response()->json($event);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'ville_id' => 'required|exists:villes,id',
            'date_evenement' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
        ]);

        $event = Event::create($data + ['delegue_id' => Auth::id(), 'statut' => 'planifie']);
        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'date_evenement' => 'sometimes|date',
            'lieu' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
        ]);
        $event->update($data);
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $event->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function storeInvitations(Request $request, Event $event)
    {
        $request->validate(['contact_ids' => 'required|array', 'contact_ids.*' => 'exists:contacts,id']);
        $event->contacts()->syncWithoutDetaching($request->contact_ids);
        return response()->json(['message' => 'Invitations added.']);
    }

    public function updateStatus(Request $request, Event $event, Contact $contact)
    {
        $request->validate(['statut' => 'required|string']);
        $event->contacts()->updateExistingPivot($contact->id, ['statut' => $request->statut]);
        return response()->json(['message' => 'Status updated.']);
    }
}
