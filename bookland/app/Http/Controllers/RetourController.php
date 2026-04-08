<?php
namespace App\Http\Controllers;

use App\Models\Bss;
use App\Models\BssLigne;
use App\Models\Retour;
use App\Models\Consignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RetourController extends Controller
{
    private function generateNumeroRetour($anneeScolaireId)
    {
        $annee = \App\Models\AnneeScolaire::find($anneeScolaireId);
        $yearSuffix = $annee->date_debut->format('Y');
        $last = Retour::whereHas('bss', function($q) use ($anneeScolaireId) {
            $q->where('annee_scolaire_id', $anneeScolaireId);
        })->orderBy('id', 'desc')->first();
        $nextId = $last ? intval(substr($last->numero, -4)) + 1 : 1;
        return sprintf('BR-%s-%04d', $yearSuffix, $nextId);
    }

    public function create(Bss $bss)
    {
        $user = Auth::user();
        if ($user->role === 'delegue' && $bss->delegate_id != $user->id) abort(403);
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if (!$delegateIds->contains($bss->delegate_id)) abort(403);
        }
        if ($bss->statut !== 'livre') abort(403, 'Seul un BSS livré peut faire l\'objet d\'un retour.');
        $bss->load('lignes.product');
        return view('retours.create', compact('bss'));
    }

    public function store(Request $request, Bss $bss)
    {
        $user = Auth::user();
        if ($user->role === 'delegue' && $bss->delegate_id != $user->id) abort(403);
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if (!$delegateIds->contains($bss->delegate_id)) abort(403);
        }

        $validated = $request->validate([
            'lignes' => 'required|array',
            'lignes.*.bss_ligne_id' => 'exists:bss_lignes,id',
            'lignes.*.quantite_retournee' => 'required|integer|min:1',
            'motif' => 'nullable|string'
        ]);

        DB::transaction(function () use ($bss, $validated, $user) {
            $retour = Retour::create([
                'numero' => $this->generateNumeroRetour($bss->annee_scolaire_id),
                'bss_id' => $bss->id,
                'date_retour' => now(),
                'created_by' => $user->id,
                'motif' => $validated['motif'] ?? null
            ]);

            foreach ($validated['lignes'] as $item) {
                $ligne = BssLigne::findOrFail($item['bss_ligne_id']);
                $retour->lignes()->attach($ligne->id, ['quantite_retournee' => $item['quantite_retournee']]);

                $ligne->date_retour = now();
                $ligne->statut_ligne = 'retourne';
                $ligne->save();

                // Réintégration dans la consignation si la source du BSS était consignation
                if ($bss->source === 'consignation') {
                    $cons = Consignation::firstOrCreate([
                        'delegate_id' => $bss->delegate_id,
                        'product_id' => $ligne->product_id,
                        'annee_scolaire_id' => $bss->annee_scolaire_id
                    ]);
                    $cons->increment('quantity', $item['quantite_retournee']);
                }
            }

            // Si toutes les lignes sont retournées, passer le BSS en statut 'retourne'
            if ($bss->lignes()->where('statut_ligne', '!=', 'retourne')->count() === 0) {
                $bss->statut = 'retourne';
                $bss->save();
            }
        });

        return redirect()->route('bss.show', $bss)->with('success', 'Bon de retour généré.');
    }

    public function show(Retour $retour)
    {
        $retour->load('bss.compte', 'lignes.bssLigne.product', 'createdBy');
        $user = Auth::user();
        $bss = $retour->bss;
        if ($user->role === 'delegue' && $bss->delegate_id != $user->id) abort(403);
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if (!$delegateIds->contains($bss->delegate_id)) abort(403);
        }
        return view('retours.show', compact('retour'));
    }
}