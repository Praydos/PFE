<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        return view('notifications.index');
    }

    public function apiIndex(Request $request)
    {
        $filters = $request->only(['type', 'category', 'is_read']);
        
        // Handle boolean conversion for is_read if passed as string
        if (isset($filters['is_read']) && $filters['is_read'] !== '') {
            $filters['is_read'] = filter_var($filters['is_read'], FILTER_VALIDATE_BOOLEAN);
        } else {
            unset($filters['is_read']);
        }

        $notifications = $this->notificationService->getForUser(auth()->id(), $filters);

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $success = $this->notificationService->markAsRead($id, auth()->id());
        
        if ($success) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->id());
        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => $this->notificationService->getUnreadCount(auth()->id())
        ]);
    }
}
