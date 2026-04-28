<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendActionReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send email reminders for upcoming actions';

    public function handle()
    {
        $now = now();
        $reminders = Action::where('rappel', true)
            ->where('date_planification', '>', $now)
            ->whereRaw("strftime('%s', date_planification) - strftime('%s', '{$now}') <= (rappel_avant * 60)")
            ->where('rappel_sent', false) // we need a `rappel_sent` boolean column
            ->with('delegate')
            ->get();

        foreach ($reminders as $action) {
            Mail::raw("Rappel: Vous avez une action '{$action->objet}' prévue pour le {$action->date_planification->format('d/m/Y')} à {$action->heure}.\n\nLieu: {$action->lieu}\nCompte: {$action->compte->etablissement}", function ($message) use ($action) {
                $message->to($action->delegate->email)
                        ->subject('Rappel action CRM');
            });
            $action->update(['rappel_sent' => true]);
        }

        $this->info('Reminders sent.');
    }
}