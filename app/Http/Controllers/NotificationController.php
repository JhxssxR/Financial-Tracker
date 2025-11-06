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
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('notifications', compact('notifications'));
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

        return response()->json(['success' => true, 'id' => $notification->id]);
    }

    /**
     * Delete (dismiss) a notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $notification->delete();

        return response()->json(['success' => true, 'id' => $notification->id]);
    }
}
