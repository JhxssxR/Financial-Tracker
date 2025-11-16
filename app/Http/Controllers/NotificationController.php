<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display the notifications page
     */
    public function index()
    {
        // Only show unread notifications in the main list (matches the UI: recent unread)
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->count();

        return view('notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read (processed)
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $notification->is_read = true;
        $notification->save();

        // Return authoritative unread count and latest unread notification (if any)
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        $latestUnread = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'id' => $notification->id,
            'newCount' => $unreadCount,
            'latestUnread' => $latestUnread ? [
                'id' => $latestUnread->id,
                'title' => $latestUnread->title,
                'message' => $latestUnread->message,
                'type' => $latestUnread->type,
                'created_at' => $latestUnread->created_at->toDateTimeString(),
            ] : null,
        ]);
    }

    /**
     * Delete (dismiss) a notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        // Instead of permanently deleting the notification, mark it as read/dismissed.
        // This prevents server-generated budget notifications from being recreated
        // if the budgets page runs the creation logic again.
        $notification->is_read = true;
        $notification->save();

        // Return authoritative unread count and latest unread
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        $latestUnread = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'id' => $notification->id,
            'message' => 'Notification dismissed',
            'newCount' => $unreadCount,
            'latestUnread' => $latestUnread ? [
                'id' => $latestUnread->id,
                'title' => $latestUnread->title,
                'message' => $latestUnread->message,
                'type' => $latestUnread->type,
                'created_at' => $latestUnread->created_at->toDateTimeString(),
            ] : null,
        ]);
    }

    /**
     * Return authoritative unread count and latest unread notification.
     */
    public function unreadCount()
    {
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        $latestUnread = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'newCount' => $unreadCount,
            'latestUnread' => $latestUnread ? [
                'id' => $latestUnread->id,
                'title' => $latestUnread->title,
                'message' => $latestUnread->message,
                'type' => $latestUnread->type,
                'created_at' => $latestUnread->created_at->toDateTimeString(),
            ] : null,
        ]);
    }
}
