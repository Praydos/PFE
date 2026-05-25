<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch due reminders and scheduled notifications';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Starting scheduled notifications dispatch...');

        Notification::pending()->chunk(100, function ($notifications) use ($notificationService) {
            foreach ($notifications as $notification) {
                $notification->update(['sent_at' => now()]);
                $notificationService->broadcastToUser($notification);
                $this->info("Dispatched notification {$notification->id} to user {$notification->user_id}");
            }
        });

        $this->info('Finished dispatching notifications.');
    }
}
