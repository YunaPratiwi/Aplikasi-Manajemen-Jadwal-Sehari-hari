<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        if ($request->ajax()) {
            return response()->json([
                'notifications' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'total' => $notifications->total()
                ],
                'unread_count' => Auth::user()->unreadNotifications()->count()
            ]);
        }
        
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Display only unread notifications.
     */
    public function unread(Request $request)
    {
        $notifications = Auth::user()->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        if ($request->ajax()) {
            return response()->json([
                'notifications' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'total' => $notifications->total()
                ],
                'unread_count' => $notifications->total()
            ]);
        }
        
        return view('notifications.unread', compact('notifications'));
    }
    
    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if (!$notification) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Notification not found'], 404);
            }
            return redirect()->back()->with('error', 'Notification not found');
        }
        
        $notification->markAsRead();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => Auth::user()->unreadNotifications()->count()
            ]);
        }
        
        return redirect()->back()->with('success', 'Notification marked as read');
    }
    
    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request)
    {
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "All {$unreadCount} notifications marked as read",
                'unread_count' => 0
            ]);
        }
        
        return redirect()->back()->with('success', "All {$unreadCount} notifications marked as read");
    }
    
    /**
     * Delete a specific notification.
     */
    public function destroy(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if (!$notification) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Notification not found'], 404);
            }
            return redirect()->back()->with('error', 'Notification not found');
        }
        
        $notification->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
                'unread_count' => Auth::user()->unreadNotifications()->count()
            ]);
        }
        
        return redirect()->back()->with('success', 'Notification deleted successfully');
    }
    
    /**
     * Get notification count for real-time updates.
     */
    public function count()
    {
        return response()->json([
            'unread_count' => Auth::user()->unreadNotifications()->count(),
            'total_count' => Auth::user()->notifications()->count()
        ]);
    }
    
    /**
     * Get recent notifications for dropdown/popup display.
     */
    public function recent()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'is_unread' => is_null($notification->read_at)
                ];
            });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count()
        ]);
    }
}