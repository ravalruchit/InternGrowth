<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /** Full notifications page */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Mark all as read when user opens the page
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    /** Mark a single notification as read (AJAX) */
    public function markRead($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /** Mark all as read (AJAX) */
    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /** Unread count for bell badge (AJAX polling) */
    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /** Recent notifications for the bell dropdown (AJAX) */
    public function dropdown()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($n) => [
                'id'          => $n->id,
                'title'       => $n->title,
                'message'     => $n->message,
                'type'        => $n->type,
                'is_read'     => $n->is_read,
                'time_ago'    => $n->created_at->diffForHumans(),
                'target_url'  => $n->target_url,
            ]);

        return response()->json(['notifications' => $notifications]);
    }

    /** Delete a single notification */
    public function destroy($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Notification deleted.');
    }

    /** Delete all notifications */
    public function destroyAll()
    {
        Notification::where('user_id', auth()->id())->delete();

        return back()->with('success', 'All notifications cleared.');
    }
}
