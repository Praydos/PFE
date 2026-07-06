<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tache;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TacheController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = Tache::with(['contact', 'assignedTo', 'createdBy']);

        if ($user->role === 'delegue') {
            $query->where(
                fn($q) =>
                $q->where('assigned_to', $user->id)->orWhere('created_by', $user->id)
            );
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id')->push($user->id);
            $query->whereIn('assigned_to', $delegueIds);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->scopedQuery();
        if ($request->filled('statut'))
            $query->where('statut', $request->statut);
        return response()->json($query->latest()->paginate(50));
    }

    public function show(Tache $tache)
    {
        $tache->load(['contact', 'assignedTo', 'createdBy']);
        return response()->json($tache);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_id' => 'nullable|exists:contacts,id',
            'assigned_to' => 'required|exists:users,id',
            'date_echeance' => 'required|date',
            'priorite' => 'nullable|string|max:50',
            'recurrence' => 'nullable|string|max:50',
        ]);

        $tache = Tache::create($data + [
            'created_by' => Auth::id(),
            'statut' => 'a_faire',
        ]);
        return response()->json($tache, 201);
    }

    public function update(Request $request, Tache $tache)
    {
        $data = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'date_echeance' => 'sometimes|date',
            'priorite' => 'nullable|string|max:50',
        ]);
        $tache->update($data);
        return response()->json($tache);
    }

    public function destroy(Tache $tache)
    {
        $tache->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function validateTache(Tache $tache)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']))
            abort(403);
        $tache->update(['statut' => 'validee']);
        return response()->json($tache);
    }

    public function cancelRecurrence(Request $request, Tache $tache)
    {
        $tache->update(['recurrence' => null]);
        return response()->json(['message' => 'Recurrence cancelled.']);
    }
}
