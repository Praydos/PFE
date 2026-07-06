<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MpDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MpDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = MpDelivery::with(['compte', 'lines.mpProduct']);

        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = \App\Models\Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
            $query->whereIn('compte_id', $compteIds);
        }

        return response()->json($query->latest()->paginate(50));
    }

    public function show(MpDelivery $mp_delivery)
    {
        $mp_delivery->load(['compte', 'lines.mpProduct']);
        return response()->json($mp_delivery);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo']))
            abort(403);

        $data = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'date_livraison' => 'required|date',
            'observations' => 'nullable|string',
            'lines' => 'required|array|min:1',
            'lines.*.mp_product_id' => 'required|exists:mp_products,id',
            'lines.*.quantite' => 'required|integer|min:1',
        ]);

        $delivery = MpDelivery::create([
            'compte_id' => $data['compte_id'],
            'date_livraison' => $data['date_livraison'],
            'observations' => $data['observations'] ?? null,
            'numero' => $this->generateNumero(),
        ]);

        foreach ($data['lines'] as $line) {
            $delivery->lines()->create($line);
        }

        return response()->json($delivery->load('lines.mpProduct'), 201);
    }

    public function destroy(MpDelivery $mp_delivery)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $mp_delivery->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    private function generateNumero(): string
    {
        $year = now()->year;
        $count = MpDelivery::whereYear('created_at', $year)->count() + 1;
        return sprintf('MP-%d-%04d', $year, $count);
    }
}
