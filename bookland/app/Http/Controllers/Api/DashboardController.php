<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Formation;
use App\Models\Examen;
use App\Models\Reclamation;
use App\Models\DemandeSpecimen;
use App\Models\Tache;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = Auth::user();

        // Scope to the authenticated user's domain
        $compteIds = collect();
        if ($user->role === 'delegue') {
            $compteIds = $user->comptes()->pluck('id');
        } elseif ($user->role === 'rbo') {
            $delegueIds = $user->supervisedDelegates()->pluck('id');
            $compteIds = Compte::whereIn('delegue_id', $delegueIds)->pluck('id');
        } else {
            // admin sees all
            $compteIds = Compte::pluck('id');
        }

        $stats = [
            'actions' => [
                'total' => Action::whereIn('compte_id', $compteIds)->count(),
                'planifiees' => Action::whereIn('compte_id', $compteIds)->where('statut', 'planifiee')->count(),
                'realisees' => Action::whereIn('compte_id', $compteIds)->where('statut', 'realisee')->count(),
                'validees' => Action::whereIn('compte_id', $compteIds)->where('statut', 'validee')->count(),
            ],
            'formations' => [
                'total' => Formation::when($user->role === 'delegue', fn($q) => $q->where('delegue_id', $user->id))
                    ->when($user->role === 'rbo', fn($q) => $q->whereIn('delegue_id', $user->supervisedDelegates()->pluck('id')))
                    ->count(),
            ],
            'examens' => [
                'total' => Examen::whereIn('compte_id', $compteIds)->count(),
            ],
            'reclamations' => [
                'total' => Reclamation::when($user->role === 'delegue', fn($q) => $q->where('delegue_id', $user->id))
                    ->when($user->role === 'rbo', fn($q) => $q->whereIn('delegue_id', $user->supervisedDelegates()->pluck('id')))
                    ->count(),
                'ouvertes' => Reclamation::when($user->role === 'delegue', fn($q) => $q->where('delegue_id', $user->id))
                    ->when($user->role === 'rbo', fn($q) => $q->whereIn('delegue_id', $user->supervisedDelegates()->pluck('id')))
                    ->where('statut', 'ouverte')->count(),
            ],
            'taches' => [
                'total' => Tache::where('assigned_to', $user->id)->count(),
                'a_faire' => Tache::where('assigned_to', $user->id)->where('statut', 'a_faire')->count(),
                'en_retard' => Tache::where('assigned_to', $user->id)->where('statut', 'a_faire')
                    ->where('date_echeance', '<', now()->toDateString())->count(),
            ],
            'comptes' => [
                'total' => $compteIds->count(),
            ],
        ];

        return response()->json($stats);
    }
}
