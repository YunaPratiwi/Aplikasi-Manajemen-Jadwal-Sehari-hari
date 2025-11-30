<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display the main search page.
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $results = [];
        
        if ($query) {
            // Search tasks
            $tasks = Auth::user()->tasks()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->with('category')
                ->limit(10)
                ->get();
            
            // Search categories
            $categories = Auth::user()->categories()
                ->where('name', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();
            
            $results = [
                'tasks' => $tasks,
                'categories' => $categories,
                'query' => $query
            ];
        }
        
        return view('search.index', compact('results', 'query'));
    }
    
    /**
     * Search for tasks specifically.
     */
    public function tasks(Request $request)
    {
        $query = $request->get('q', '');
        $tasks = collect();
        
        if ($query) {
            $tasks = Auth::user()->tasks()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'total' => $tasks->total()
                ]
            ]);
        }
        
        return view('search.tasks', compact('tasks', 'query'));
    }
    
    /**
     * Search for categories specifically.
     */
    public function categories(Request $request)
    {
        $query = $request->get('q', '');
        $categories = collect();
        
        if ($query) {
            $categories = Auth::user()->categories()
                ->where('name', 'LIKE', "%{$query}%")
                ->withCount('tasks')
                ->orderBy('name')
                ->paginate(10);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'categories' => $categories->items(),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'total' => $categories->total()
                ]
            ]);
        }
        
        return view('search.categories', compact('categories', 'query'));
    }
    
    /**
     * Get search suggestions for autocomplete.
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        $suggestions = [];
        
        if (strlen($query) >= 2) {
            // Get task title suggestions
            $taskSuggestions = Auth::user()->tasks()
                ->where('title', 'LIKE', "%{$query}%")
                ->select('title')
                ->distinct()
                ->limit(5)
                ->pluck('title')
                ->map(function ($title) {
                    return [
                        'type' => 'task',
                        'text' => $title,
                        'icon' => 'task'
                    ];
                });
            
            // Get category suggestions
            $categorySuggestions = Auth::user()->categories()
                ->where('name', 'LIKE', "%{$query}%")
                ->select('name')
                ->limit(3)
                ->pluck('name')
                ->map(function ($name) {
                    return [
                        'type' => 'category',
                        'text' => $name,
                        'icon' => 'folder'
                    ];
                });
            
            $suggestions = $taskSuggestions->concat($categorySuggestions)->take(8);
        }
        
        return response()->json($suggestions);
    }
    
    /**
     * Advanced search with multiple filters.
     */
    public function advanced(Request $request)
    {
        $query = Auth::user()->tasks()->with('category');
        
        // Apply search filters
        if ($request->filled('title')) {
            $query->where('title', 'LIKE', "%{$request->title}%");
        }
        
        if ($request->filled('description')) {
            $query->where('description', 'LIKE', "%{$request->description}%");
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->due_date_from);
        }
        
        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->due_date_to);
        }
        
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
        
        $tasks = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Auth::user()->categories()->orderBy('name')->get();
        
        if ($request->ajax()) {
            return response()->json([
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'total' => $tasks->total()
                ]
            ]);
        }
        
        return view('search.advanced', compact('tasks', 'categories'));
    }
}