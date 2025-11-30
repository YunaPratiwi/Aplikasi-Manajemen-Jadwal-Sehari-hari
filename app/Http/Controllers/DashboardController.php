<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan user memiliki relasi tasks dan categories
        if (!$user) {
            return redirect()->route('login');
        }

        // Hitung statistik task
        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $pendingTasks = $user->tasks()->where('status', 'pending')->count();
        $overdueTasks = $user->tasks()->where('due_date', '<', now())->where('status', '!=', 'completed')->count();
        $tasksDueToday = $user->tasks()->whereDate('due_date', now())->count();

        // Ambil 5 task terbaru (dengan relasi kategori)
        $recentTasks = $user->tasks()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Hitung kategori aktif
        $categoriesCount = $user->categories()->count();

        return view('dashboard.index', compact(
            'user',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'overdueTasks',
            'tasksDueToday',
            'recentTasks',
            'categoriesCount'
        ));
    }

    /**
     * Statistik produktivitas user (API).
     */
    public function userStats()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!method_exists($user, 'getProductivityStats')) {
            return response()->json(['error' => 'Method getProductivityStats() tidak ditemukan pada model User.'], 500);
        }

        return response()->json($user->getProductivityStats());
    }
}
