<?php

namespace App\Http\Controllers;

use App\Models\Bss;
use App\Models\Retour;
use App\Models\RetourLigne;
use App\Models\Consignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetourController extends Controller
{
    private function generateNumero()
    {
        $last = Retour::orderBy('id', 'desc')->first();
        $nextId = $last ? intval(substr($last->numero, -4)) + 1 : 1;
        return 'RET-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function create(Bss $bss)
    {
        $bss->load('lignes.product');
        return view('retours.create', compact('bss'));
    }

    public function store(Request $request, Bss $bss)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.ligne_id' => 'required|exists:bss_lignes,id',
            'items.*.quantite' => 'required|integer|min:1',
            'motif' => 'nullable|string',
            'date_retour' => 'required|date',
        ]);

        $retour = Retour::create([
            'numero' => $this->generateNumero(),
            'bss_id' => $bss->id,
            'date_retour' => $validated['date_retour'],
            'created_by' => Auth::id(),
            'motif' => $validated['motif'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $ligne = $bss->lignes()->find($item['ligne_id']);
            $quantite = $item['quantite'];

            RetourLigne::create([
                'retour_id' => $retour->id,
                'bss_ligne_id' => $ligne->id,
                'quantite_retournee' => $quantite,
            ]);

            if ($ligne->quantite == $quantite) {
                $ligne->statut_ligne = 'retournee';
            } else {
                $ligne->statut_ligne = 'partiel';
            }
            $ligne->date_retour = $validated['date_retour'];
            $ligne->save();

            // Restore stock to consignation
            if ($bss->source === 'consignation') {
                $cons = Consignation::where('delegate_id', $bss->delegate_id)
                    ->where('product_id', $ligne->product_id)
                    ->where('annee_scolaire_id', $bss->annee_scolaire_id)
                    ->first();
                if ($cons) {
                    $cons->increment('quantity', $quantite);
                }
            }
        }

        $remainingLines = $bss->lignes()->whereNotIn('statut_ligne', ['retournee'])->count();
        if ($remainingLines == 0) {
            $bss->statut = 'retourne';
        } else {
            $bss->statut = 'partiel';
        }
        $bss->save();

        return redirect()->route('bss.show', $bss)->with('success', 'Retour enregistré.');
    }
}