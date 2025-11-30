<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'priority',
        'status',
        'is_completed',
        'due_date',
        'reminder_at',
        'completed_at',
        'sort_order',
        'tags',
        'attachments',
        'estimated_minutes',
        'actual_minutes',
        'is_recurring',
        'recurring_settings',
        'is_archived',
        'archived_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'datetime',
        'reminder_at' => 'datetime',
        'completed_at' => 'datetime',
        'archived_at' => 'datetime',
        'tags' => 'array',
        'attachments' => 'array',
        'is_recurring' => 'boolean',
        'recurring_settings' => 'array',
        'is_archived' => 'boolean',
        'sort_order' => 'integer',
        'estimated_minutes' => 'integer',
        'actual_minutes' => 'integer',
    ];

    /**
     * Priority levels.
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    /**
     * Status types.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($task) {
            if ($task->isDirty('is_completed')) {
                if ($task->is_completed) {
                    $task->completed_at = now();
                    $task->status = self::STATUS_COMPLETED;
                } else {
                    $task->completed_at = null;
                    $task->status = self::STATUS_PENDING;
                }
            }
        });
    }

    /**
     * Get the user that owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the task.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the collaborators for the task.
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_collaborators')
                    ->withPivot(['role', 'can_edit', 'can_delete', 'can_invite', 'invited_at', 'accepted_at'])
                    ->withTimestamps();
    }
    
    /**
     * Get the comments for the task.
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class)->with('user')->latest();
    }

    /**
     * Scope a query to only include active tasks.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope a query to only include completed tasks.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to only include pending tasks.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('is_completed', false)
                    ->where('due_date', '<', now());
    }

    /**
     * Scope a query to only include tasks due today.
     */
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->where('is_completed', false)
                    ->whereDate('due_date', today());
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to search tasks.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }

    /**
     * Scope a query to order by priority.
     */
    public function scopeOrderByPriority(Builder $query, string $direction = 'desc'): Builder
    {
        $priorityOrder = [
            self::PRIORITY_HIGH => 3,
            self::PRIORITY_MEDIUM => 2,
            self::PRIORITY_LOW => 1,
        ];

        return $query->orderByRaw(
            "CASE priority 
                WHEN 'high' THEN 3 
                WHEN 'medium' THEN 2 
                WHEN 'low' THEN 1 
                ELSE 0 
            END {$direction}"
        );
    }

    /**
     * Check if the task is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return !$this->is_completed && 
               $this->due_date && 
               $this->due_date->isPast();
    }

    /**
     * Check if the task is due today.
     */
    public function getIsDueTodayAttribute(): bool
    {
        return !$this->is_completed && 
               $this->due_date && 
               $this->due_date->isToday();
    }

    /**
     * Get the task's priority color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_HIGH => '#EF4444',
            self::PRIORITY_MEDIUM => '#F59E0B',
            self::PRIORITY_LOW => '#10B981',
            default => '#6B7280',
        };
    }

    /**
     * Get the task's status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_COMPLETED => '#10B981',
            self::STATUS_IN_PROGRESS => '#3B82F6',
            self::STATUS_CANCELLED => '#EF4444',
            default => '#6B7280',
        };
    }

    /**
     * Get formatted due date.
     */
    public function getFormattedDueDateAttribute(): ?string
    {
        if (!$this->due_date) {
            return null;
        }

        if ($this->due_date->isToday()) {
            return 'Today at ' . $this->due_date->format('H:i');
        }

        if ($this->due_date->isTomorrow()) {
            return 'Tomorrow at ' . $this->due_date->format('H:i');
        }

        if ($this->due_date->isYesterday()) {
            return 'Yesterday at ' . $this->due_date->format('H:i');
        }

        return $this->due_date->format('M j, Y \a\t H:i');
    }

    /**
     * Get time tracking information.
     */
    public function getTimeTrackingAttribute(): array
    {
        return [
            'estimated_hours' => $this->estimated_minutes ? round($this->estimated_minutes / 60, 2) : null,
            'actual_hours' => $this->actual_minutes ? round($this->actual_minutes / 60, 2) : null,
            'time_variance' => $this->estimated_minutes && $this->actual_minutes 
                ? $this->actual_minutes - $this->estimated_minutes 
                : null,
        ];
    }

    /**
     * Mark task as completed.
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'status' => self::STATUS_COMPLETED,
        ]);
    }

    /**
     * Mark task as pending.
     */
    public function markAsPending(): bool
    {
        return $this->update([
            'is_completed' => false,
            'completed_at' => null,
            'status' => self::STATUS_PENDING,
        ]);
    }

    /**
     * Archive the task.
     */
    public function archive(): bool
    {
        return $this->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);
    }

    /**
     * Unarchive the task.
     */
    public function unarchive(): bool
    {
        return $this->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);
    }

    /**
     * Add a tag to the task.
     */
    public function addTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    /**
     * Remove a tag from the task.
     */
    public function removeTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        $tags = array_filter($tags, fn($t) => $t !== $tag);
        $this->update(['tags' => array_values($tags)]);
    }

    /**
     * Get available priority options.
     */
    public static function getPriorityOptions(): array
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
        ];
    }

    /**
     * Get available status options.
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}