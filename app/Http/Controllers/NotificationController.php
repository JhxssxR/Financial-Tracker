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
        // Show all notifications (read + unread) in the main list (paginated)
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Keep an authoritative unread count separate from the paginated results
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

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
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'newCount' => 0,
            'latestUnread' => null,
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

        // Permanently delete the notification so it will not reappear after refresh.
        // If you prefer a soft-dismiss behavior (mark-as-read) instead, revert to setting
        // `is_read = true` here. Deleting removes the row from the DB.
        $notification->delete();

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
