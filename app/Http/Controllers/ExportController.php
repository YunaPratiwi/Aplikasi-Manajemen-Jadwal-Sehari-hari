<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    /**
     * Export tasks to CSV.
     */
    public function tasksCSV(Request $request): Response
    {
        $user = Auth::user();
        $query = $user->tasks()->with('category');
        
        // Apply filters if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $tasks = $query->get();
        
        // Build CSV
        $csv = "ID,Title,Description,Category,Priority,Status,Due Date,Created At,Completed At\n";
        
        foreach ($tasks as $task) {
            $csv .= implode(',', [
                $task->id,
                '"' . str_replace('"', '""', $task->title) . '"',
                '"' . str_replace('"', '""', $task->description ?? '') . '"',
                '"' . ($task->category?->name ?? 'N/A') . '"',
                $task->priority,
                $task->status,
                $task->due_date ? $task->due_date->format('Y-m-d H:i:s') : 'N/A',
                $task->created_at->format('Y-m-d H:i:s'),
                $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : 'N/A',
            ]) . "\n";
        }
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tasks-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Export tasks to PDF (placeholder - would need dompdf package).
     */
    public function tasksPDF(Request $request): Response
    {
        $user = Auth::user();
        $tasks = $user->tasks()->with('category')->get();
        
        // Generate simple HTML for PDF
        $html = '<h1>Tasks Export</h1>';
        $html .= '<p>User: ' . $user->name . '</p>';
        $html .= '<p>Exported: ' . now()->format('Y-m-d H:i:s') . '</p>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th>ID</th><th>Title</th><th>Category</th><th>Priority</th><th>Status</th><th>Due Date</th></tr>';
        
        foreach ($tasks as $task) {
            $html .= '<tr>';
            $html .= '<td>' . $task->id . '</td>';
            $html .= '<td>' . htmlspecialchars($task->title) . '</td>';
            $html .= '<td>' . ($task->category?->name ?? 'N/A') . '</td>';
            $html .= '<td>' . ucfirst($task->priority) . '</td>';
            $html .= '<td>' . ucfirst($task->status) . '</td>';
            $html .= '<td>' . ($task->due_date ? $task->due_date->format('Y-m-d') : 'N/A') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        // Note: For production, use a PDF library like dompdf or wkhtmltopdf
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="tasks-' . now()->format('Y-m-d') . '.html"',
        ]);
    }

    /**
     * Export tasks to Excel (placeholder - would need PhpSpreadsheet).
     */
    public function tasksExcel(Request $request): Response
    {
        // For production, implement with PhpSpreadsheet
        // For now, return CSV with Excel mime type
        return $this->tasksCSV($request);
    }

    /**
     * Custom export with filters.
     */
    public function customTasksExport(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        switch ($format) {
            case 'pdf':
                return $this->tasksPDF($request);
            case 'excel':
                return $this->tasksExcel($request);
            case 'json':
                return $this->tasksJSON($request);
            default:
                return $this->tasksCSV($request);
        }
    }
    
    /**
     * Export tasks to JSON.
     */
    public function tasksJSON(Request $request): Response
    {
        $user = Auth::user();
        $tasks = $user->tasks()->with('category', 'comments', 'collaborators')->get();
        
        $data = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'exported_at' => now()->toIso8601String(),
            'total_tasks' => $tasks->count(),
            'tasks' => $tasks,
        ];
        
        return response(json_encode($data, JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="tasks-' . now()->format('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Export categories to CSV.
     */
    public function categoriesCSV(): Response
    {
        $user = Auth::user();
        $categories = $user->categories()->withCount('tasks')->get();
        
        $csv = "ID,Name,Description,Color,Icon,Tasks Count,Created At\n";
        
        foreach ($categories as $category) {
            $csv .= implode(',', [
                $category->id,
                '"' . str_replace('"', '""', $category->name) . '"',
                '"' . str_replace('"', '""', $category->description ?? '') . '"',
                $category->color ?? 'N/A',
                $category->icon ?? 'N/A',
                $category->tasks_count,
                $category->created_at->format('Y-m-d H:i:s'),
            ]) . "\n";
        }
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="categories-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Export categories to PDF.
     */
    public function categoriesPDF(): Response
    {
        $user = Auth::user();
        $categories = $user->categories()->withCount('tasks')->get();
        
        $html = '<h1>Categories Export</h1>';
        $html .= '<p>User: ' . $user->name . '</p>';
        $html .= '<p>Exported: ' . now()->format('Y-m-d H:i:s') . '</p>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th>ID</th><th>Name</th><th>Description</th><th>Tasks Count</th></tr>';
        
        foreach ($categories as $category) {
            $html .= '<tr>';
            $html .= '<td>' . $category->id . '</td>';
            $html .= '<td>' . htmlspecialchars($category->name) . '</td>';
            $html .= '<td>' . htmlspecialchars($category->description ?? 'N/A') . '</td>';
            $html .= '<td>' . $category->tasks_count . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="categories-' . now()->format('Y-m-d') . '.html"',
        ]);
    }
    
    /**
     * Export summary report.
     */
    public function summaryReport(): Response
    {
        $user = Auth::user();
        
        $data = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'generated_at' => now()->toIso8601String(),
            'statistics' => [
                'total_tasks' => $user->tasks()->count(),
                'completed_tasks' => $user->tasks()->completed()->count(),
                'pending_tasks' => $user->tasks()->pending()->count(),
                'overdue_tasks' => $user->tasks()->overdue()->count(),
                'total_categories' => $user->categories()->count(),
            ],
            'categories' => $user->categories()->withCount('tasks')->get()->map(function($cat) {
                return [
                    'name' => $cat->name,
                    'tasks_count' => $cat->tasks_count,
                ];
            }),
        ];
        
        return response(json_encode($data, JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="summary-' . now()->format('Y-m-d') . '.json"',
        ]);
    }
    
    /**
     * Export detailed report.
     */
    public function detailedReport(): Response
    {
        $user = Auth::user();
        
        $data = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'member_since' => $user->created_at->toIso8601String(),
            ],
            'generated_at' => now()->toIso8601String(),
            'statistics' => $user->getProductivityStats(),
            'categories' => $user->categories()->with('tasks')->get(),
            'recent_tasks' => $user->tasks()->with('category')->latest()->limit(50)->get(),
        ];
        
        return response(json_encode($data, JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="detailed-report-' . now()->format('Y-m-d') . '.json"',
        ]);
    }
}

