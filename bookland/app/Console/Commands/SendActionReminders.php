<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * @deprecated Action reminders are handled via NotificationObserver + notifications:send-scheduled.
 */
class SendActionReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Deprecated — use notifications:send-scheduled instead';

    public function handle(): int
    {
        $this->warn('This command is deprecated. Action reminders use the in-app notification system.');
        $this->info('Run: php artisan notifications:send-scheduled');
        $this->info('Ensure the scheduler is running: php artisan schedule:work');

        return self::SUCCESS;
    }
}
