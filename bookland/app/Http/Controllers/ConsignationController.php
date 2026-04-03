<?php

namespace App\Http\Controllers;

use App\Models\Consignation;
use App\Models\User;
use App\Models\AnneScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ConsignationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeYear = AnneeScolaire::getActive();
        if (!$activeYear) {
            return redirect()->route('annees-scolaires.index')
                ->with('error', 'Aucune année scolaire active. Veuillez en définir une.');
        }

        $query = Consignation::with(['delegate', 'product', 'anneeScolaire'])
            ->where('annee_scolaire_id', $activeYear->id);

        // Role-based filtering
        if ($user->role === 'delegue') {
            $query->where('delegate_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegate_id', $delegateIds);
        }
        // admin sees all

        // Optional filter by delegate (for admin/RBO)
        if (in_array($user->role, ['admin', 'rbo']) && $request->filled('delegate_id')) {
            $query->where('delegate_id', $request->delegate_id);
        }

        $consignations = $query->paginate(20);

        // Build delegate list for filter
        $delegates = null;
        if (in_array($user->role, ['admin', 'rbo'])) {
            if ($user->role === 'admin') {
                $delegates = User::where('role', 'delegue')->orderBy('nom')->get();
            } else {
                $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
                $delegates = User::whereIn('id', $delegateIds)->orderBy('nom')->get();
            }
        }

        return view('consignations.index', compact('consignations', 'delegates', 'activeYear'));
    }

    public function edit(Consignation $consignation)
    {
        Gate::authorize('update', $consignation);
        return view('consignations.edit', compact('consignation'));
    }

    public function update(Request $request, Consignation $consignation)
    {
        Gate::authorize('update', $consignation);

        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $consignation->update(['quantity' => $request->quantity]);

        return redirect()->route('consignations.index')
            ->with('success', 'Stock mis à jour.');
    }
}