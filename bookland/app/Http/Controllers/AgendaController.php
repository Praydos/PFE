<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Examen;
use App\Models\Formation;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        $events = [];

        // Actions
        $actions = Action::with('compte')
            ->whereBetween('date_planification', [$start, $end])
            ->when($user->role === 'delegue', fn($q) => $q->where('delegue_id', $user->id))
            ->when($user->role === 'rbo', function($q) use ($user) {
                $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
                $q->whereIn('delegue_id', $delegateIds);
            })
            ->get();
        foreach ($actions as $a) {
            $events[] = [
                'title' => $a->objet,
                'start' => $a->date_planification->format('Y-m-d') . ($a->heure ? 'T' . $a->heure : ''),
                'url' => route('actions.show', $a),
                'color' => $this->getColorForAction($a->statut),
                'type' => 'action',
            ];
        }

        // Examens
        $examens = Examen::whereNotNull('date_examen')
            ->whereBetween('date_examen', [$start, $end])
            ->when($user->role === 'delegue', fn($q) => $q->where('delegue_id', $user->id))
            ->when($user->role === 'rbo', function($q) use ($user) {
                $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
                $q->whereIn('delegue_id', $delegateIds);
            })
            ->get();
        foreach ($examens as $e) {
            $events[] = [
                'title' => 'Examen: ' . $e->titre,
                'start' => $e->date_examen->format('Y-m-d'),
                'url' => route('examens.show', $e),
                'color' => '#6f42c1',
                'type' => 'examen',
            ];
        }

        // Formations – you'll need a real date field, e.g., the first proposed date or a "date_formation" column.
        // For now, skip or use date_demande.

        // Events
        $eventsList = Event::whereBetween('date_event', [$start, $end])
            ->when($user->role === 'delegue', fn($q) => $q->where('delegue_id', $user->id))
            ->when($user->role === 'rbo', function($q) use ($user) {
                $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
                $q->whereIn('delegue_id', $delegateIds);
            })
            ->get();
        foreach ($eventsList as $ev) {
            $events[] = [
                'title' => 'Événement: ' . $ev->type,
                'start' => $ev->date_event->format('Y-m-d'),
                'url' => route('events.show', $ev),
                'color' => '#fd7e14',
                'type' => 'event',
            ];
        }

        if ($request->wantsJson()) {
            return response()->json($events);
        }

        return view('agenda.index', compact('events'));
    }

    private function getColorForAction($statut)
    {
        return match($statut) {
            'planifie' => '#ffc107',
            'realise' => '#28a745',
            'valide' => '#17a2b8',
            'annule' => '#dc3545',
            'reporte' => '#6f42c1',
            default => '#6c757d',
        };
    }
}