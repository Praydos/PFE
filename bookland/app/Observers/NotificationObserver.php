<?php

namespace App\Observers;

use App\Models\Action;
use App\Models\ActionAmelioration;
use App\Models\Bss;
use App\Models\DemandeSpecimen;
use App\Models\Event;
use App\Models\Examen;
use App\Models\Formation;
use App\Models\NonConformite;
use App\Models\Reclamation;
use App\Models\Tache;
use App\Models\Notification;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NotificationObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(Model $model): void
    {
        $info = $this->getModelInfo($model);
        if (!$info || !$info['user']) {
            return;
        }

        // 1. Success Notification
        $targetUser = auth()->user() ?? $info['user'];
        $title = "Nouveau {$info['category']}";
        $message = "Votre {$info['category']} a été créé(e) avec succès.";
        $this->notificationService->createSuccess($targetUser, $info['category'], $title, $message, $model);

        // 2. Schedule Reminder
        $this->scheduleReminder($model, $info);
    }

    public function updated(Model $model): void
    {
        $info = $this->getModelInfo($model);
        if (!$info || !$info['user']) {
            return;
        }

        // 1. Success Notification for Update (send to the person who triggered the update, or the linked user)
        // Actually, we'll send it to the auth user if available so they know their action succeeded
        $targetUser = auth()->user() ?? $info['user'];
        $title = "Mise à jour: {$info['category']}";
        $message = "Votre {$info['category']} a été mis(e) à jour avec succès.";
        $this->notificationService->createSuccess($targetUser, $info['category'], $title, $message, $model);

        if ($this->shouldRefreshReminder($model, $info)) {
            $this->deletePendingReminders($model);
            $this->scheduleReminder($model, $info);
        }
    }

    public function deleted(Model $model): void
    {
        $info = $this->getModelInfo($model);
        if (!$info || !$info['user']) {
            return;
        }

        $targetUser = auth()->user() ?? $info['user'];
        $title = "Suppression: {$info['category']}";
        $message = "Votre {$info['category']} a été supprimé(e) avec succès.";
        // We do not pass the model here since it's deleted and polymorphic relations might fail
        $this->notificationService->createSuccess($targetUser, $info['category'], $title, $message);

        // Delete any pending reminders
        Notification::where('notifiable_type', get_class($model))
            ->where('notifiable_id', $model->id)
            ->delete();
    }

    protected function scheduleReminder(Model $model, array $info): void
    {
        if ($model instanceof Action && !$model->rappel) {
            return;
        }

        if (!$info['dateValue']) {
            return;
        }

        // For formation which has array of dates
        $dateValue = is_array($info['dateValue']) ? ($info['dateValue'][0] ?? null) : $info['dateValue'];
        if (!$dateValue) {
            return;
        }

        $eventDate = Carbon::parse($dateValue)->startOfDay();

        if (!empty($info['timeValue'])) {
            $timeParts = explode(':', $info['timeValue']);
            $eventDate->setTime((int) $timeParts[0], (int) ($timeParts[1] ?? 0));
        } else {
            $eventDate->setHour(9);
        }

        $reminderDate = $eventDate->copy();
        if ($model instanceof Action) {
            $minutesBefore = (int) ($model->rappel_avant ?? 0);
            if ($minutesBefore < 1) {
                return;
            }
            $reminderDate->subMinutes($minutesBefore);
        }

        if (!$reminderDate->isFuture()) {
            return;
        }

        $title = "Rappel: {$info['category']} prévu(e)";
        $timeStr = !empty($info['timeValue']) ? ' à ' . substr($info['timeValue'], 0, 5) : '';
        $eventLabel = $eventDate->format('d/m/Y') . $timeStr;
        if ($model instanceof Action) {
            $message = "Votre action « {$model->objet} » est prévue le {$eventLabel}.";
        } else {
            $message = "Vous avez un(e) {$info['category']} prévu(e) pour le {$eventLabel}.";
        }

        $this->notificationService->scheduleReminder(
            $info['user'],
            $info['category'],
            $title,
            $message,
            $reminderDate,
            $model
        );
    }

    protected function shouldRefreshReminder(Model $model, array $info): bool
    {
        if ($model->wasChanged($info['dateField']) || $model->wasChanged('heure')) {
            return true;
        }

        return $model instanceof Action
            && ($model->wasChanged('rappel') || $model->wasChanged('rappel_avant'));
    }

    protected function deletePendingReminders(Model $model): void
    {
        Notification::where('notifiable_type', get_class($model))
            ->where('notifiable_id', $model->id)
            ->where('type', 'reminder')
            ->whereNull('sent_at')
            ->delete();
    }

    protected function getModelInfo(Model $model): ?array
    {
        $user = method_exists($model, 'delegate') ? $model->delegate : (method_exists($model, 'user') ? $model->user : null);
        // Fallback for user relation name if different
        if (!$user && isset($model->delegue_id)) {
            $user = \App\Models\User::find($model->delegue_id);
        }
        
        if (!$user) return null;

        $info = ['user' => $user];

        if ($model instanceof Tache) {
            $info['category'] = 'tache';
            $info['dateField'] = 'date_planification';
            $info['dateValue'] = $model->date_planification;
            $info['timeValue'] = $model->heure ?? null;
        } elseif ($model instanceof Action) {
            $info['category'] = 'action';
            $info['dateField'] = 'date_planification';
            $info['dateValue'] = $model->date_planification;
            $info['timeValue'] = $model->heure ?? null;
        } elseif ($model instanceof DemandeSpecimen) {
            $info['category'] = 'specimen';
            $info['dateField'] = 'date_demande';
            $info['dateValue'] = $model->date_demande;
            $info['timeValue'] = null;
        } elseif ($model instanceof Event) {
            $info['category'] = 'event';
            $info['dateField'] = 'date_event';
            $info['dateValue'] = $model->date_event;
            $info['timeValue'] = null;
        } elseif ($model instanceof Examen) {
            $info['category'] = 'examen';
            $info['dateField'] = 'date_examen';
            $info['dateValue'] = $model->date_examen;
            $info['timeValue'] = null;
        } elseif ($model instanceof Formation) {
            $info['category'] = 'formation';
            $info['dateField'] = 'date_demande';
            $info['dateValue'] = $model->date_demande; // This is an array
            $info['timeValue'] = null;
        } elseif ($model instanceof Bss) {
            $info['category'] = 'bss';
            $info['dateField'] = 'date_livraison_prevue';
            $info['dateValue'] = $model->date_livraison_prevue;
            $info['timeValue'] = null;
            if (!$user && isset($model->delegate_id)) {
                $user = \App\Models\User::find($model->delegate_id);
                $info['user'] = $user;
            }
        } elseif ($model instanceof Reclamation) {
            $info['category'] = 'reclamation';
            $info['dateField'] = 'date_reclamation';
            $info['dateValue'] = $model->date_reclamation;
            $info['timeValue'] = null;
        } elseif ($model instanceof NonConformite) {
            $info['category'] = 'non_conformite';
            $info['dateField'] = 'date_nc';
            $info['dateValue'] = $model->date_nc;
            $info['timeValue'] = null;
        } elseif ($model instanceof ActionAmelioration) {
            $info['category'] = 'action_amelioration';
            $info['dateField'] = 'date_suivi';
            $info['dateValue'] = $model->date_suivi;
            $info['timeValue'] = null;
        } else {
            return null;
        }

        return $info;
    }
}
