<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get the authenticated user's notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
                              ->paginate(15);

        return response()->json([
            'status' => 'success',
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }

    /**
     * Get the unread count.
     */
    public function unreadCount()
    {
        return response()->json([
            'status' => 'success',
            'unread_count' => Auth::user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Delete a specific notification.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->delete();
            return response()->json(['status' => 'success', 'message' => 'Notification deleted successfully']);
        }

        return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        Auth::user()->notifications()->delete();
        return response()->json(['status' => 'success', 'message' => 'All notifications deleted successfully']);
    }

    /**
     * Return the Pharmacy Notifications blade view.
     */
    public function pharmacyPage()
    {
        return view('pharmacy.notifications.index');
    }

    /**
     * Return the Admin Notifications blade view.
     */
    public function adminPage()
    {
        return view('admin.notifications.index');
    }

    /**
     * Admin sends a custom notification.
     */
    public function sendCustomNotification(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'target'  => 'required|in:users,pharmacies,both',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'type'    => 'required|in:info,success,warning,error'
        ]);

        $query = \App\Models\User::query();

        if ($request->target === 'users') {
            $query->where('role', 'user');
        } elseif ($request->target === 'pharmacies') {
            $query->where('role', 'pharmacy');
        } else {
            $query->whereIn('role', ['user', 'pharmacy']);
        }

        $users = $query->get();

        if ($users->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send(
                $users, 
                new \App\Notifications\SystemNotification(
                    $request->title,
                    $request->message,
                    $request->type,
                    null // no action url for custom notification
                )
            );

            \App\Models\AdminSentNotification::create([
                'target' => $request->target,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'recipients_count' => $users->count()
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Notification sent successfully']);
    }

    public function sentHistory()
    {
        $history = \App\Models\AdminSentNotification::orderBy('created_at', 'desc')->paginate(15);
        return response()->json($history);
    }
}
