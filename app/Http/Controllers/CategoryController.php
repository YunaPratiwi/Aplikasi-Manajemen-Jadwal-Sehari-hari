<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Helper method to get the base query for the current user's categories.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    private function userCategoriesQuery()
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->categories();
    }

    /**
     * Display a listing of the resource.
     * Method ini yang TIDAK ADA dan menyebabkan error.
     */
    public function index()
    {
        $categories = $this->userCategoriesQuery()
            ->withCount('tasks')
            ->ordered()
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,user_id,' . auth()->id(),
            'color' => 'nullable|string|max:7', // e.g., #FFFFFF
            'description' => 'nullable|string|max:1000',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $category = $user->categories()->create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori "' . $category->name . '" berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        
        $tasks = $category->tasks()->with('category')->paginate(10);
        
        return view('categories.show', compact('category', 'tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id . ',id,user_id,' . auth()->id(),
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori "' . $category->name . '" berhasil diperbarui.');
    }
    
    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        
        // Check if category has tasks
        $taskCount = $category->tasks()->count();
        
        if ($taskCount > 0) {
            return redirect()->back()
                ->with('error', "Tidak dapat menghapus kategori karena masih memiliki {$taskCount} task. Pindahkan atau hapus task terlebih dahulu.");
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
    
    /**
     * Toggle category active status.
     */
    public function toggleActive(Category $category)
    {
        $this->authorize('update', $category);
        
        $category->update(['is_active' => !$category->is_active]);
        
        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
            ->with('success', "Kategori berhasil {$status}.");
    }
    
    /**
     * Show archived (inactive) categories.
     */
    public function archived(Request $request)
    {
        $query = $this->userCategoriesQuery()->onlyTrashed();
        
        // If using soft deletes, show only trashed categories
        if (method_exists(Category::class, 'bootSoftDeletes')) {
            $categories = $query->withCount('tasks')
                ->ordered()
                ->paginate(15);
        } else {
            // Fallback to is_active = false if not using soft deletes
            $categories = $query->where('is_active', false)
                ->withCount('tasks')
                ->ordered()
                ->paginate(15);
        }
        
        return view('categories.archived', compact('categories'));
    }
}