<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskCollaborator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'role',
        'can_edit',
        'can_delete',
        'can_invite',
        'invited_at',
        'accepted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
        'can_invite' => 'boolean',
        'invited_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Role constants.
     */
    const ROLE_VIEWER = 'viewer';
    const ROLE_EDITOR = 'editor';
    const ROLE_OWNER = 'owner';

    /**
     * Get the task that owns the collaboration.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user that is collaborating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the collaboration is accepted.
     */
    public function getIsAcceptedAttribute(): bool
    {
        return !is_null($this->accepted_at);
    }

    /**
     * Check if the collaboration is pending.
     */
    public function getIsPendingAttribute(): bool
    {
        return is_null($this->accepted_at);
    }

    /**
     * Accept the collaboration invitation.
     */
    public function accept(): bool
    {
        return $this->update(['accepted_at' => now()]);
    }

    /**
     * Get available role options.
     */
    public static function getRoleOptions(): array
    {
        return [
            self::ROLE_VIEWER => 'Viewer',
            self::ROLE_EDITOR => 'Editor',
            self::ROLE_OWNER => 'Owner',
        ];
    }

    /**
     * Get role permissions.
     */
    public function getPermissions(): array
    {
        return [
            'can_view' => true,
            'can_edit' => $this->can_edit,
            'can_delete' => $this->can_delete,
            'can_invite' => $this->can_invite,
        ];
    }
}