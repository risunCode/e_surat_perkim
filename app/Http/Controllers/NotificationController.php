<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())->latest();
        
        $notifications = $this->paginateQuery($query, $request, 20);

        return view('pages.notifications.index', compact('notifications'));
    }

    public function getUnread()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())->unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get realtime notification data with badge counts
     */
    public function getRealtime()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        // Get unread notifications
        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', $userId)->unread()->count();

        // Count today's unread by type for sidebar badges
        $incomingCount = Notification::where('user_id', $userId)
            ->where('type', 'incoming')
            ->where('is_read', false)
            ->whereDate('created_at', $today)
            ->count();

        $outgoingCount = Notification::where('user_id', $userId)
            ->where('type', 'outgoing')
            ->where('is_read', false)
            ->whereDate('created_at', $today)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'incoming_count' => $incomingCount,
            'outgoing_count' => $outgoingCount,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
