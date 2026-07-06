<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Contact::with('comptes');

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereHas('comptes', fn($q) => $q->whereIn('comptes.id', $compteIds));
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereHas('comptes', fn($q) => $q->whereIn('comptes.id', $compteIds));
        }

        return response()->json($query->latest()->paginate(50));
    }

    public function show(Contact $contact)
    {
        $contact->load('comptes');
        return response()->json($contact);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string|max:20',
            'fonction' => 'nullable|string|max:100',
        ]);
        $contact = Contact::create($data);
        return response()->json($contact, 201);
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string|max:20',
            'fonction' => 'nullable|string|max:100',
        ]);
        $contact->update($data);
        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $contact->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function getComptes(Contact $contact)
    {
        return response()->json($contact->comptes);
    }

    public function updateComptes(Request $request, Contact $contact)
    {
        $request->validate(['compte_ids' => 'array', 'compte_ids.*' => 'exists:comptes,id']);
        $contact->comptes()->sync($request->compte_ids ?? []);
        return response()->json(['message' => 'Updated.']);
    }

    public function printList(Request $request)
    {
        return response()->json(['message' => 'Use the web app for PDF generation.'], 400);
    }
}
