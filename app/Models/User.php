<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'theme_preference',
        'email_notifications',
        'push_notifications',
        'notification_settings',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'notification_settings' => 'array',
    ];

    /**
     * Get the user's tasks.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the user's categories.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get tasks where user is a collaborator.
     */
    public function collaboratedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_collaborators')
                    ->withPivot(['role', 'can_edit', 'can_delete', 'can_invite', 'invited_at', 'accepted_at'])
                    ->withTimestamps();
    }

    /**
     * Get all tasks accessible by the user (owned + collaborated).
     */
    public function accessibleTasks()
    {
        return Task::where('user_id', $this->id)
                   ->orWhereHas('collaborators', function ($query) {
                       $query->where('user_id', $this->id);
                   });
    }

    /**
     * Get completed tasks count.
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tasks()->where('is_completed', true)->count();
    }

    /**
     * Get pending tasks count.
     */
    public function getPendingTasksCountAttribute(): int
    {
        return $this->tasks()->where('is_completed', false)->count();
    }

    /**
     * Get overdue tasks count.
     */
    public function getOverdueTasksCountAttribute(): int
    {
        return $this->tasks()
                    ->where('is_completed', false)
                    ->where('due_date', '<', now())
                    ->count();
    }

    /**
     * Get tasks due today count.
     */
    public function getTasksDueTodayCountAttribute(): int
    {
        return $this->tasks()
                    ->where('is_completed', false)
                    ->whereDate('due_date', today())
                    ->count();
    }

    /**
     * Get user's productivity stats.
     */
    public function getProductivityStats(): array
    {
        $totalTasks = $this->tasks()->count();
        $completedTasks = $this->completed_tasks_count;
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $this->pending_tasks_count,
            'overdue_tasks' => $this->overdue_tasks_count,
            'tasks_due_today' => $this->tasks_due_today_count,
            'completion_rate' => $completionRate,
        ];
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Check if user prefers dark mode.
     */
    public function prefersDarkMode(): bool
    {
        return $this->theme_preference === 'dark';
    }

    /**
     * Get user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Generate avatar using initials
        $initials = collect(explode(' ', $this->name))
                   ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                   ->take(2)
                   ->implode('');

        return "https://ui-avatars.com/api/?name={$initials}&color=ffffff&background=3B82F6&size=128";
    }
}
