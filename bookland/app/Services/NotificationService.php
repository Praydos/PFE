<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Events\NewNotification;

class NotificationService
{
    /**
     * Create a success notification (immediately visible)
     */
    public function createSuccess(User $user, string $category, string $title, string $message, $model = null): Notification
    {
        return $this->create($user, 'success', $category, $title, $message, null, $model);
    }

    /**
     * Create a reminder scheduled for a future time
     */
    public function scheduleReminder(User $user, string $category, string $title, string $message, \DateTimeInterface $scheduledAt, $model = null): Notification
    {
        return $this->create($user, 'reminder', $category, $title, $message, $scheduledAt, $model);
    }

    /**
     * Create a deadline alert
     */
    public function createDeadline(User $user, string $category, string $title, string $message, $model = null): Notification
    {
        return $this->create($user, 'deadline', $category, $title, $message, null, $model);
    }

    /**
     * Create a system notification
     */
    public function createSystem(User $user, string $title, string $message): Notification
    {
        return $this->create($user, 'system', 'system', $title, $message, null, null);
    }

    /**
     * Base creation method
     */
    private function create(User $user, string $type, string $category, string $title, string $message, ?\DateTimeInterface $scheduledAt = null, $model = null): Notification
    {
        $notification = new Notification([
            'user_id' => $user->id,
            'type' => $type,
            'category' => $category,
            'title' => $title,
            'message' => $message,
            'scheduled_at' => $scheduledAt,
            // If it's not scheduled for the future, mark it sent immediately
            'sent_at' => $scheduledAt && $scheduledAt > now() ? null : now(),
        ]);

        if ($model) {
            $notification->notifiable_type = get_class($model);
            $notification->notifiable_id = $model->id;
        }

        $notification->save();

        // Broadcast immediately if it's sent
        if ($notification->sent_at) {
            $this->broadcastToUser($notification);
        }

        return $notification;
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true]) > 0;
    }

    public function markAllAsRead(int $userId): void
    {
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getForUser(int $userId, array $filters = [], int $perPage = 15)
    {
        $query = Notification::forUser($userId)
            ->where(function($q) {
                // Only show notifications that have been sent
                $q->whereNotNull('sent_at');
            })
            ->latest('created_at');

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (!empty($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['is_read'])) {
            $query->where('is_read', $filters['is_read']);
        }

        return $query->paginate($perPage);
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::forUser($userId)
            ->whereNotNull('sent_at')
            ->unread()
            ->count();
    }

    public function broadcastToUser(Notification $notification): void
    {
        if (class_exists(NewNotification::class)) {
            broadcast(new NewNotification($notification));
        }
    }
}
