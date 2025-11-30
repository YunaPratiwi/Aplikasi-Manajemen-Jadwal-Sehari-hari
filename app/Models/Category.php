<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'sort_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the user that owns the category.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks for the category.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get active tasks for the category.
     */
    public function activeTasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('is_archived', false);
    }

    /**
     * Get completed tasks for the category.
     */
    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('is_completed', true);
    }

    /**
     * Get pending tasks for the category.
     */
    public function pendingTasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('is_completed', false);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order categories by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the tasks count for the category.
     */
    public function getTasksCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Get the completed tasks count for the category.
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tasks()->where('is_completed', true)->count();
    }

    /**
     * Get the pending tasks count for the category.
     */
    public function getPendingTasksCountAttribute(): int
    {
        return $this->tasks()->where('is_completed', false)->count();
    }

    /**
     * Get the completion percentage for the category.
     */
    public function getCompletionPercentageAttribute(): float
    {
        $totalTasks = $this->tasks_count;
        if ($totalTasks === 0) {
            return 0;
        }

        return round(($this->completed_tasks_count / $totalTasks) * 100, 2);
    }

    /**
     * Get the category statistics.
     */
    public function getStats(): array
    {
        return [
            'total_tasks' => $this->tasks_count,
            'completed_tasks' => $this->completed_tasks_count,
            'pending_tasks' => $this->pending_tasks_count,
            'completion_percentage' => $this->completion_percentage,
        ];
    }

    /**
     * Generate a unique slug for the category.
     */
    public function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('user_id', $this->user_id)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get default categories for a new user.
     */
    public static function getDefaultCategories(): array
    {
        return [
            [
                'name' => 'Personal',
                'description' => 'Personal tasks and activities',
                'color' => '#3B82F6',
                'icon' => 'user',
                'sort_order' => 1,
            ],
            [
                'name' => 'Work',
                'description' => 'Work-related tasks and projects',
                'color' => '#10B981',
                'icon' => 'briefcase',
                'sort_order' => 2,
            ],
            [
                'name' => 'Shopping',
                'description' => 'Shopping lists and purchases',
                'color' => '#F59E0B',
                'icon' => 'shopping-cart',
                'sort_order' => 3,
            ],
            [
                'name' => 'Health',
                'description' => 'Health and fitness related tasks',
                'color' => '#EF4444',
                'icon' => 'heart',
                'sort_order' => 4,
            ],
        ];
    }

    /**
     * Create default categories for a user.
     */
    public static function createDefaultCategories(User $user): void
    {
        foreach (static::getDefaultCategories() as $categoryData) {
            $categoryData['user_id'] = $user->id;
            static::create($categoryData);
        }
    }
}