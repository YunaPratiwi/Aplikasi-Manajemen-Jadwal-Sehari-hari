<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $query = $user->tasks()->with('category');

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'pending') {
                $query->pending();
            } else {
                $query->byStatus($request->status);
            }
        }

        if ($request->filled('due_date')) {
            switch ($request->due_date) {
                case 'today':
                    $query->dueToday();
                    break;
                case 'overdue':
                    $query->overdue();
                    break;
                case 'this_week':
                    $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
            }
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if ($sortBy === 'priority') {
            $query->orderByPriority($sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Only show active tasks by default
        if (!$request->has('show_archived')) {
            $query->active();
        }

        $tasks = $query->paginate(15)->appends($request->query());
        $categories = $user->categories()->active()->ordered()->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->active()->ordered()->get();
        return view('tasks.create', compact('categories'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after:now',
            'reminder_at' => 'nullable|date|after:now',
            'tags' => 'nullable|string',
            'estimated_minutes' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify category belongs to user
        $category = Category::where('user_id', Auth::id())->findOrFail($request->category_id);

        $taskData = [
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => Task::STATUS_PENDING,
            'due_date' => $request->due_date,
            'reminder_at' => $request->reminder_at,
            'estimated_minutes' => $request->estimated_minutes,
            'sort_order' => Task::where('user_id', Auth::id())->max('sort_order') + 1,
        ];

        // Process tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $taskData['tags'] = array_filter($tags);
        }

        $task = Task::create($taskData);

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil dibuat.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load(['category', 'collaborators.user']);
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $categories = $user->categories()->active()->ordered()->get();
        $task->load('category');
        
        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'reminder_at' => 'nullable|date',
            'tags' => 'nullable|string',
            'estimated_minutes' => 'nullable|integer|min:1',
            'actual_minutes' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify category belongs to user
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $category = $user->categories()->findOrFail($request->category_id);

        $taskData = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'reminder_at' => $request->reminder_at,
            'estimated_minutes' => $request->estimated_minutes,
            'actual_minutes' => $request->actual_minutes,
        ];

        // Process tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $taskData['tags'] = array_filter($tags);
        } else {
            $taskData['tags'] = [];
        }

        // Handle completion status
        if ($request->status === Task::STATUS_COMPLETED && !$task->is_completed) {
            $taskData['is_completed'] = true;
            $taskData['completed_at'] = now();
        } elseif ($request->status !== Task::STATUS_COMPLETED && $task->is_completed) {
            $taskData['is_completed'] = false;
            $taskData['completed_at'] = null;
        }

        $task->update($taskData);

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil diperbarui.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil dihapus.');
    }

    /**
     * Toggle task completion status.
     */
    public function toggle(Task $task)
    {
        $this->authorize('update', $task);

        if ($task->is_completed) {
            $task->markAsPending();
            $message = 'Task ditandai sebagai belum selesai.';
        } else {
            $task->markAsCompleted();
            $message = 'Task ditandai sebagai selesai.';
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_completed' => $task->is_completed,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Archive the specified task.
     */
    public function archive(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->archive();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil diarsipkan.',
            ]);
        }

        return redirect()->back()->with('success', 'Task berhasil diarsipkan.');
    }

    /**
     * Unarchive the specified task.
     */
    public function unarchive(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->unarchive();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dipulihkan dari arsip.',
            ]);
        }

        return redirect()->back()->with('success', 'Task berhasil dipulihkan dari arsip.');
    }

    /**
     * Show archived tasks.
     */
    public function archived(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $query = $user->tasks()->with('category')->where('is_archived', true);

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $tasks = $query->orderBy('archived_at', 'desc')->paginate(15);
        $categories = $user->categories()->active()->ordered()->get();

        return view('tasks.archived', compact('tasks', 'categories'));
    }

    /**
     * Reorder tasks.
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request) {
            $user = Auth::user();
            /** @var \App\Models\User $user */
            foreach ($request->task_ids as $index => $taskId) {
                $task = $user->tasks()->findOrFail($taskId);
                $task->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Urutan task berhasil diperbarui.']);
    }

    /**
     * Get search suggestions.
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $user = Auth::user();
        /** @var \App\Models\User $user */
        $tasks = $user->tasks()
            ->select('title')
            ->where('title', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('title');

        $tags = $user->tasks()
            ->whereJsonContains('tags', $query)
            ->limit(5)
            ->pluck('tags')
            ->flatten()
            ->filter(function ($tag) use ($query) {
                return stripos($tag, $query) !== false;
            })
            ->unique()
            ->take(5);

        return response()->json([
            'tasks' => $tasks,
            'tags' => $tags->values(),
        ]);
    }

    /**
     * Get task statistics for API.
     */
    public function stats()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        $stats = [
            'total' => $user->tasks()->active()->count(),
            'completed' => $user->tasks()->active()->completed()->count(),
            'pending' => $user->tasks()->active()->pending()->count(),
            'overdue' => $user->tasks()->active()->overdue()->count(),
            'due_today' => $user->tasks()->active()->dueToday()->count(),
            'high_priority' => $user->tasks()->active()->byPriority('high')->count(),
            'medium_priority' => $user->tasks()->active()->byPriority('medium')->count(),
            'low_priority' => $user->tasks()->active()->byPriority('low')->count(),
        ];

        $stats['completion_rate'] = $stats['total'] > 0 
            ? round(($stats['completed'] / $stats['total']) * 100, 1) 
            : 0;

        return response()->json($stats);
    }

    /**
     * Update time tracking for a task.
     */
    public function updateTimeTracking(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validator = Validator::make($request->all(), [
            'actual_minutes' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $task->update(['actual_minutes' => $request->actual_minutes]);

        return response()->json([
            'success' => true,
            'message' => 'Waktu tracking berhasil diperbarui.',
            'time_tracking' => $task->time_tracking,
        ]);
    }

    /**
     * API endpoint for tasks listing.
     */
    public function apiIndex(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $query = $user->tasks()->with('category');

        // Apply filters similar to index method
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'pending') {
                $query->pending();
            } else {
                $query->byStatus($request->status);
            }
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if ($sortBy === 'priority') {
            $query->orderByPriority($sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $query->active();

        $tasks = $query->paginate($request->get('per_page', 15));

        return response()->json($tasks);
    }
    
    /**
     * Duplicate the specified task.
     */
    public function duplicate(Task $task)
    {
        $this->authorize('view', $task);
        
        // Replicate the task
        $newTask = $task->replicate();
        $newTask->title = $task->title . ' (Copy)';
        $newTask->is_completed = false;
        $newTask->completed_at = null;
        $newTask->is_archived = false;
        $newTask->archived_at = null;
        $newTask->save();
        
        // Copy tags if exist
        if ($task->tags) {
            $newTask->tags = $task->tags;
            $newTask->save();
        }
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil diduplikasi.',
                'task' => $newTask,
            ]);
        }
        
        return redirect()->route('tasks.edit', $newTask)
            ->with('success', 'Task berhasil diduplikasi. Silakan edit jika diperlukan.');
    }
    
    /**
     * Perform bulk action on tasks.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:complete,incomplete,archive,delete,change_priority,change_category',
            'task_ids' => 'required|array|min:1',
            'task_ids.*' => 'exists:tasks,id',
            'priority' => 'required_if:action,change_priority|in:low,medium,high',
            'category_id' => 'required_if:action,change_category|exists:categories,id',
        ]);
        
        if ($validator->fails()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $tasks = Task::whereIn('id', $request->task_ids)
            ->where('user_id', $user->id)
            ->get();
        
        if ($tasks->isEmpty()) {
            $message = 'Tidak ada task yang ditemukan.';
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return redirect()->back()->with('error', $message);
        }
        
        $count = 0;
        foreach ($tasks as $task) {
            try {
                switch ($request->action) {
                    case 'complete':
                        if (!$task->is_completed) {
                            $task->markAsCompleted();
                            $count++;
                        }
                        break;
                        
                    case 'incomplete':
                        if ($task->is_completed) {
                            $task->markAsPending();
                            $count++;
                        }
                        break;
                        
                    case 'archive':
                        if (!$task->is_archived) {
                            $task->archive();
                            $count++;
                        }
                        break;
                        
                    case 'delete':
                        $task->delete();
                        $count++;
                        break;
                        
                    case 'change_priority':
                        $task->update(['priority' => $request->priority]);
                        $count++;
                        break;
                        
                    case 'change_category':
                        $task->update(['category_id' => $request->category_id]);
                        $count++;
                        break;
                }
            } catch (\Exception $e) {
                // Continue with other tasks if one fails
                continue;
            }
        }
        
        $message = $count . ' task berhasil diperbarui.';
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count,
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Add comment to task.
     */
    public function addComment(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:5000',
        ]);
        
        if ($validator->fails()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment berhasil ditambahkan.',
                'comment' => $comment->load('user'),
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment berhasil ditambahkan.');
    }
    
    /**
     * Delete comment from task.
     */
    public function deleteComment(Task $task, $commentId)
    {
        $comment = $task->comments()->findOrFail($commentId);
        
        // Only comment owner or task owner can delete
        if ($comment->user_id !== auth()->id() && $task->user_id !== auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }
        
        $comment->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment berhasil dihapus.',
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment berhasil dihapus.');
    }
}
