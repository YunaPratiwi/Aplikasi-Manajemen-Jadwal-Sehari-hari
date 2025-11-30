<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the main reports dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Basic statistics
        $stats = [
            'total_tasks' => $user->tasks()->count(),
            'completed_tasks' => $user->tasks()->where('status', 'completed')->count(),
            'pending_tasks' => $user->tasks()->where('status', 'pending')->count(),
            'overdue_tasks' => $user->tasks()->where('due_date', '<', now())->where('status', '!=', 'completed')->count(),
            'total_categories' => $user->categories()->count(),
        ];
        
        // Calculate completion rate
        $stats['completion_rate'] = $stats['total_tasks'] > 0 
            ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 1) 
            : 0;
        
        // Recent activity (last 7 days)
        $recentActivity = $user->tasks()
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->with('category')
            ->get();
        
        // Tasks by category
        $tasksByCategory = $user->categories()
            ->withCount('tasks')
            ->orderBy('tasks_count', 'desc')
            ->limit(5)
            ->get();
        
        if ($request->ajax()) {
            return response()->json([
                'stats' => $stats,
                'recent_activity' => $recentActivity,
                'tasks_by_category' => $tasksByCategory
            ]);
        }
        
        return view('reports.index', compact('stats', 'recentActivity', 'tasksByCategory'));
    }
    
    /**
     * Generate productivity report.
     */
    public function productivity(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);
        
        // Daily task completion over the period
        $dailyCompletion = Task::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $startDate)
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Tasks by priority completion
        $priorityStats = Task::where('user_id', Auth::id())
            ->where('updated_at', '>=', $startDate)
            ->select('priority', 'status', DB::raw('COUNT(*) as count'))
            ->groupBy('priority', 'status')
            ->get()
            ->groupBy('priority');
        
        // Average completion time
        $avgCompletionTime = Task::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $startDate)
            ->whereNotNull('due_date')
            ->select(DB::raw('AVG(DATEDIFF(updated_at, created_at)) as avg_days'))
            ->first();
        
        // Most productive hours
        $productiveHours = Task::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $startDate)
            ->select(DB::raw('HOUR(updated_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        $data = [
            'period' => $period,
            'daily_completion' => $dailyCompletion,
            'priority_stats' => $priorityStats,
            'avg_completion_time' => $avgCompletionTime->avg_days ?? 0,
            'productive_hours' => $productiveHours
        ];
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('reports.productivity', $data);
    }
    
    /**
     * Generate time tracking report.
     */
    public function timeTracking(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);
        
        // Time spent by category (if time tracking is implemented)
        $timeByCategory = Auth::user()->categories()
            ->withSum(['tasks' => function ($query) use ($startDate) {
                $query->where('updated_at', '>=', $startDate);
            }], 'estimated_hours')
            ->get();
        
        // Tasks with time estimates vs actual
        $timeEstimates = Task::where('user_id', Auth::id())
            ->where('updated_at', '>=', $startDate)
            ->whereNotNull('estimated_hours')
            ->select('title', 'estimated_hours', 'actual_hours', 'status', 'category_id')
            ->with('category')
            ->get();
        
        // Weekly time distribution
        $weeklyTime = Task::where('user_id', Auth::id())
            ->where('updated_at', '>=', $startDate)
            ->select(
                DB::raw('WEEK(updated_at) as week'),
                DB::raw('SUM(COALESCE(actual_hours, estimated_hours, 0)) as total_hours')
            )
            ->groupBy('week')
            ->orderBy('week')
            ->get();
        
        $data = [
            'period' => $period,
            'time_by_category' => $timeByCategory,
            'time_estimates' => $timeEstimates,
            'weekly_time' => $weeklyTime
        ];
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('reports.time-tracking', $data);
    }
    
    /**
     * Generate completion report.
     */
    public function completion(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);
        
        // Completion trends
        $completionTrend = Task::where('user_id', Auth::id())
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Completion by category
        $completionByCategory = Auth::user()->categories()
            ->withCount(['tasks' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }])
            ->withCount(['tasks as completed_tasks_count' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('status', 'completed');
            }])
            ->get()
            ->map(function ($category) {
                $category->completion_rate = $category->tasks_count > 0 
                    ? round(($category->completed_tasks_count / $category->tasks_count) * 100, 1)
                    : 0;
                return $category;
            });
        
        // On-time completion rate
        $onTimeCompletion = Task::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $startDate)
            ->whereNotNull('due_date')
            ->select(
                DB::raw('SUM(CASE WHEN updated_at <= due_date THEN 1 ELSE 0 END) as on_time'),
                DB::raw('COUNT(*) as total')
            )
            ->first();
        
        $data = [
            'period' => $period,
            'completion_trend' => $completionTrend,
            'completion_by_category' => $completionByCategory,
            'on_time_rate' => $onTimeCompletion->total > 0 
                ? round(($onTimeCompletion->on_time / $onTimeCompletion->total) * 100, 1)
                : 0
        ];
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('reports.completion', $data);
    }
    
    /**
     * Generate team report (if collaboration features exist).
     */
    public function team(Request $request)
    {
        // This would be expanded if team/collaboration features are implemented
        $user = Auth::user();
        
        // For now, show individual user stats in a team context
        $teamStats = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'total_tasks' => $user->tasks()->count(),
            'completed_tasks' => $user->tasks()->where('status', 'completed')->count(),
            'active_categories' => $user->categories()->count(),
            'productivity_score' => $this->calculateProductivityScore($user)
        ];
        
        // Placeholder for team collaboration data
        $collaborationData = [
            'shared_tasks' => 0,
            'team_members' => 1,
            'team_completion_rate' => $teamStats['total_tasks'] > 0 
                ? round(($teamStats['completed_tasks'] / $teamStats['total_tasks']) * 100, 1)
                : 0
        ];
        
        $data = [
            'team_stats' => $teamStats,
            'collaboration_data' => $collaborationData
        ];
        
        if ($request->ajax()) {
            return response()->json($data);
        }
        
        return view('reports.team', $data);
    }
    
    /**
     * Calculate productivity score for a user.
     */
    private function calculateProductivityScore($user)
    {
        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $onTimeTasks = $user->tasks()
            ->where('status', 'completed')
            ->whereNotNull('due_date')
            ->whereRaw('updated_at <= due_date')
            ->count();
        
        if ($totalTasks === 0) {
            return 0;
        }
        
        $completionRate = ($completedTasks / $totalTasks) * 100;
        $onTimeRate = $completedTasks > 0 ? ($onTimeTasks / $completedTasks) * 100 : 0;
        
        // Weighted score: 70% completion rate + 30% on-time rate
        return round(($completionRate * 0.7) + ($onTimeRate * 0.3), 1);
    }
}