<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ... [keep all other methods as they are]
    
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

    // ... [keep all other methods as they are]
}
