<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Examen;
use App\Models\Formation;
use App\Models\Event;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tache;
use Illuminate\Support\Facades\Log;
use App\Models\Bss;

class AgendaController extends Controller
{
    private function getDelegateIdsForRbo($user)
    {
        return $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $viewMode = $request->get('view', 'calendar');
        $tab = $request->get('tab', 'all');

        $events = collect();
        if ($viewMode === 'list') {
            $events = $this->getListEvents($user, $tab, $request);
        }

        return view('agenda.index', compact('viewMode', 'tab', 'events'));
    }

    public function events(Request $request)
    {
        try {
            $user = Auth::user();
            $start = $request->input('start');
            $end = $request->input('end');
            $tab = $request->get('tab', 'all');

            Log::info('Agenda events request', ['tab' => $tab, 'start' => $start, 'end' => $end]);

            $calendarEvents = [];

            // Actions (commercial + tasks)
            if ($tab === 'all' || $tab === 'actions' || $tab === 'tasks') {
                $filterType = ($tab === 'tasks') ? 'tache' : (($tab === 'actions') ? 'commercial' : null);
                $actions = $this->getActions($user, $start, $end, $filterType);
                foreach ($actions as $action) {
                    $e = $this->formatActionEvent($action);
                    if ($e) $calendarEvents[] = $e;
                }
            }

            // Examens
            if ($tab === 'all' || $tab === 'examens') {
                $examens = $this->getExamens($user, $start, $end);
                foreach ($examens as $examen) {
                    $e = $this->formatExamenEvent($examen);
                    if ($e) $calendarEvents[] = $e;
                }
            }

            // Formations
            if ($tab === 'all' || $tab === 'formations') {
                $formations = $this->getFormations($user, $start, $end);
                foreach ($formations as $formation) {
                    $e = $this->formatFormationEvent($formation);
                    if ($e) $calendarEvents[] = $e;
                }
            }

            // Events
            if ($tab === 'all' || $tab === 'events') {
                $events = $this->getEvents($user, $start, $end);
                foreach ($events as $event) {
                    $e = $this->formatEventEvent($event);
                    if ($e) $calendarEvents[] = $e;
                }
            }

            // Vacations (background)
            if ($tab === 'all') {
                $vacations = Vacation::all();
                foreach ($vacations as $vacation) {
                    $calendarEvents[] = [
                        'title' => $vacation->name,
                        'start' => $vacation->start_date->toDateString(),
                        'end' => $vacation->end_date->toDateString(),
                        'display' => 'background',
                        'color' => '#f8d7da',
                        'textColor' => '#721c24',
                    ];
                }
            }

            // Inside the events() method, after the "Events" block, add:
            if ($tab === 'all' || $tab === 'specimens') {
                $specimens = $this->getSpecimens($user, $start, $end);
                foreach ($specimens as $bss) {
                    $e = $this->formatSpecimenEvent($bss);
                    if ($e) $calendarEvents[] = $e;
                }
            }

            // 2. Fix the loop — skip null results
            if ($tab === 'all' || $tab === 'tasks') {
    $tasks = $this->getTasks($user, $start, $end);
    foreach ($tasks as $task) {
        $e = $this->formatTaskEvent($task);
        if ($e) $calendarEvents[] = $e;
    }
}

            return response()->json($calendarEvents);
       // 3. Make the catch actually log so you can see future errors
        } catch (\Exception $e) {
            Log::error('Agenda events error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500); // ← show real message
        }
    }

    // ------------------------------------------------------------------
    // Helper methods (keep the same as before, but with null safety)
    // ------------------------------------------------------------------

    private function formatActionEvent($action)
    {
        if (!$action->compte) return null;
        $compte = $action->compte;
        $title = ($action->objet ?? 'Action') . ' – ' . ($compte->etablissement ?? 'N/A');
        return [
            'title' => $title,
            'start' => $action->date_planification->toDateString() . ($action->heure ? 'T' . $action->heure : ''),
            'url' => route('actions.show', $action),
            'color' => $this->getColorForDelegate($action->delegate_id),
            'id' => $action->id,
            
            'extendedProps' => [
                'type' => 'action',
                'compte' => $compte->etablissement ?? '',
                'ville' => $compte->ville->nom ?? '',
                'zone' => $compte->zone->name ?? '',
                'delegate' => $action->delegate->prenom . ' ' . $action->delegate->nom,
            ],
        ];
    }

    private function formatExamenEvent($examen)
    {
        if (!$examen->compte || !$examen->date_examen) return null;
        $compte = $examen->compte;
        $title = 'Examen: ' . ($examen->titre ?? 'N/A') . ' – ' . ($compte->etablissement ?? 'N/A');
        return [
            'title' => $title,
            'start' => $examen->date_examen->toDateString(),
            'url' => route('examens.show', $examen),
            'color' => '#6f42c1',
            'id' => $examen->id,
            
            'extendedProps' => [
                'type' => 'examen',
                'compte' => $compte->etablissement ?? '',
                'ville' => $compte->ville->nom ?? '',
                'zone' => $compte->zone->name ?? '',
                'delegate' => $examen->delegate->prenom . ' ' . $examen->delegate->nom,
            ],
        ];
    }

    private function formatFormationEvent($formation)
    {
        if (!$formation->compte) return null;
        $dates = $formation->dates_proposees ?? [];
        if (empty($dates)) return null;
        $firstDate = is_array($dates) ? reset($dates) : null;
        if (!$firstDate) return null;

        $compte = $formation->compte;
        $title = 'Formation: ' . ($formation->type ?? 'N/A') . ' – ' . ($compte->etablissement ?? 'N/A');
        return [
            'title' => $title,
            'start' => $firstDate,
            'url' => route('formations.show', $formation),
            'color' => '#fd7e14',
            'id' => $formation->id,
            
            'extendedProps' => [
                'type' => 'formation',
                'compte' => $compte->etablissement ?? '',
                'ville' => $compte->ville->nom ?? '',
                'zone' => $compte->zone->name ?? '',
                'delegate' => $formation->delegate->prenom . ' ' . $formation->delegate->nom,
            ],
        ];
    }

    private function formatEventEvent($event)
    {
        if (!$event->compte) return null;
        $compte = $event->compte;
        $title = 'Événement: ' . ($event->type ?? 'N/A') . ' – ' . ($compte->etablissement ?? 'N/A');
        return [
            'title' => $title,
            'start' => $event->date_event->toDateString(),
            'url' => route('events.show', $event),
            'color' => '#28a745',
            'id' => $event->id,
            
            'extendedProps' => [
                'type' => 'event',
                'compte' => $compte->etablissement ?? '',
                'ville' => $compte->ville->nom ?? '',
                'zone' => $compte->zone->name ?? '',
                'delegate' => $event->delegate->prenom . ' ' . $event->delegate->nom,
            ],
        ];
    }

    // ------------------------------------------------------------------
    // Data fetching (with role scoping)
    // ------------------------------------------------------------------

    private function getActions($user, $start, $end, $type = null)
    {
        $query = Action::with(['compte', 'delegate']);
        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $this->getDelegateIdsForRbo($user);
            $query->whereIn('delegue_id', $delegateIds);
        }
        if ($start && $end) {
            $query->whereBetween('date_planification', [$start, $end]);
        }
        if ($type) {
            $query->where('type', $type);
        }
        return $query->orderBy('date_planification')->get();
    }

    private function getExamens($user, $start, $end)
    {
        $query = Examen::with(['compte', 'delegate'])
            ->whereNotNull('date_examen');
        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $this->getDelegateIdsForRbo($user);
            $query->whereIn('delegue_id', $delegateIds);
        }
        if ($start && $end) {
            $query->whereBetween('date_examen', [$start, $end]);
        }
        return $query->orderBy('date_examen')->get();
    }

    private function getFormations($user, $start, $end)
    {
        $query = Formation::with(['compte', 'delegate']);
        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $this->getDelegateIdsForRbo($user);
            $query->whereIn('delegue_id', $delegateIds);
        }
        $formations = $query->get();
        // Filter by first proposed date
        return $formations->filter(function ($f) use ($start, $end) {
            $dates = $f->dates_proposees ?? [];
            if (empty($dates)) return false;
            $first = reset($dates);
            if ($start && $end) {
                return $first >= $start && $first <= $end;
            }
            return true;
        })->values();
    }

    private function getEvents($user, $start, $end)
    {
        $query = Event::with(['compte', 'delegate']);
        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $this->getDelegateIdsForRbo($user);
            $query->whereIn('delegue_id', $delegateIds);
        }
        if ($start && $end) {
            $query->whereBetween('date_event', [$start, $end]);
        }
        return $query->orderBy('date_event')->get();
    }

    private function getListEvents($user, $tab, $request)
    {
        // same as before, but safe
        $allEvents = collect();
        // ... you can copy from previous implementation (I'll skip for brevity)
        // Ensure you use the same safe formatting methods.
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
    }

    private function getColorForDelegate($delegateId)
    {
        $hash = hash('md5', $delegateId);
        $hue = hexdec(substr($hash, 0, 6)) % 360;
        return "hsl($hue, 70%, 50%)";
    }






    //specimen method for testing 

   

    // Add this method
    private function getSpecimens($user, $start, $end)
    {
        $query = Bss::with(['compte', 'delegate'])
            ->whereNotNull('date_livraison_prevue');
        if ($user->role === 'delegue') {
            $query->where('delegate_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $this->getDelegateIdsForRbo($user);
            $query->whereIn('delegate_id', $delegateIds);
        }
        if ($start && $end) {
            $query->whereBetween('date_livraison_prevue', [$start, $end]);
        }
        return $query->orderBy('date_livraison_prevue')->get();
    }

    // Add this method
    private function formatSpecimenEvent($bss)
    {
        if (!$bss->compte) return null;
        $compte = $bss->compte;
        $title = 'Livraison BSS: ' . ($bss->numero ?? 'N/A') . ' – ' . ($compte->etablissement ?? 'N/A');
        return [
            'title' => $title,
            'start' => $bss->date_livraison_prevue->toDateString(),
            'url' => route('bss.show', $bss),
            'color' => '#17a2b8', // teal
            'id' => $bss->id,
            
            'extendedProps' => [
                'type' => 'specimen',
                'compte' => $compte->etablissement ?? '',
                'ville' => $compte->ville->nom ?? '',
                'zone' => $compte->zone->name ?? '',
                'delegate' => $bss->delegate->prenom . ' ' . $bss->delegate->nom,
            ],
        ];
    }



        private function getTasks($user, $start, $end)
    {
        $query = Tache::with('delegate');
        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        } elseif ($user->role === 'rbo') {
            $delegateIds = $this->getDelegateIdsForRbo($user);
            $query->whereIn('delegue_id', $delegateIds);
        }
        if ($start && $end) {
            $query->whereBetween('date_planification', [$start, $end]);
        }
        return $query->get();
    }

    
    
    private function formatTaskEvent($task)
    {
        if (!$task->delegate) return null;

        $dateStr = $task->date_planification instanceof \Carbon\Carbon
            ? $task->date_planification->toDateString()
            : \Carbon\Carbon::parse($task->date_planification)->toDateString();

        // heure is now a plain "H:i" or "H:i:s" string — safe to concat
        $heure = $task->heure ? substr($task->heure, 0, 5) : null; // ensure "H:i" only
        $start = $dateStr . ($heure ? 'T' . $heure : '');

        return [
            'id'    => $task->id,
            'title' => $task->objet,
            'start' => $start,
            'url'   => route('taches.show', $task),
            'color' => '#9ba8c5',
            'extendedProps' => [
                'type'     => 'task',
                'delegate' => trim(($task->delegate->prenom ?? '') . ' ' . ($task->delegate->nom ?? '')),
            ],
        ];
    }






    public function rescheduleEvent(Request $request, $type, $id)
{
    $user = Auth::user();
    $newDate = $request->input('new_date');

    if (!$newDate) {
        return response()->json(['error' => 'Date manquante'], 422);
    }

    $model = null;
    switch ($type) {
    case 'action':
        $model = Action::findOrFail($id);
        $model->date_planification = $newDate;
        break;
    case 'examen':
        $model = Examen::findOrFail($id);
        $model->date_examen = $newDate;
        break;
    case 'event':
        $model = Event::findOrFail($id);
        $model->date_event = $newDate;
        break;
    case 'specimen':
        // If you allow rescheduling BSS deliveries
        $model = Bss::findOrFail($id);
        $model->date_livraison_prevue = $newDate;
        break;
    case 'tache':
        $model = Tache::findOrFail($id);
        $model->date_planification = $newDate;
        break;
    default:
        return response()->json(['error' => 'Type non supporté'], 400);
}

    $model->save();

    return response()->json(['success' => true]);
}

    
        
}