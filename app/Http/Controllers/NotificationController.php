<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Dapatkan daftar notifikasi pengguna saat ini (JSON atau Web view).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $user->notifications()->where('is_read', false)->count(),
                'notifications' => $notifications,
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Tandai notifikasi spesifik sebagai telah dibaca.
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $notification->markAsRead();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai telah dibaca.',
                'unread_count' => Auth::user()->notifications()->where('is_read', false)->count(),
            ]);
        }

        return back()->with('success', 'Notifikasi berhasil ditandai telah dibaca.');
    }

    /**
     * Tandai SEMUA notifikasi milik pengguna yang sedang login sebagai telah dibaca.
     */
    public function markAllAsRead(Request $request)
    {
        Auth::user()->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Seluruh notifikasi berhasil ditandai dibaca.',
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'Seluruh notifikasi berhasil ditandai telah dibaca.');
    }

    /**
     * Hapus notifikasi tertentu.
     */
    public function destroy(Request $request, Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $notification->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus.',
                'unread_count' => Auth::user()->notifications()->where('is_read', false)->count(),
            ]);
        }

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
