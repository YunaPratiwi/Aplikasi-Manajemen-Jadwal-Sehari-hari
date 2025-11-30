<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CollaborationController extends Controller
{
    /**
     * Show collaboration index page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Tasks where user is owner
        $ownedTasks = $user->tasks()
            ->with(['collaborators' => function($query) {
                $query->wherePivot('accepted_at', '!=', null);
            }])
            ->withCount('collaborators')
            ->get();
        
        // Tasks where user is collaborator
        $collaboratedTasks = $user->collaboratedTasks()
            ->with('user', 'category')
            ->wherePivot('accepted_at', '!=', null)
            ->get();
        
        // Pending invitations
        $pendingInvitations = $user->collaboratedTasks()
            ->with('user', 'category')
            ->wherePivot('accepted_at', null)
            ->get();
        
        return view('collaboration.index', compact('ownedTasks', 'collaboratedTasks', 'pendingInvitations'));
    }
    
    /**
     * Invite user to collaborate on task.
     */
    public function invite(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:viewer,editor,admin',
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'can_invite' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Find user by email
        $invitee = User::where('email', $request->email)->first();
        
        if (!$invitee) {
            $message = 'User tidak ditemukan.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Check if user is trying to invite themselves
        if ($invitee->id === auth()->id()) {
            $message = 'Anda tidak dapat mengundang diri sendiri.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Check if already a collaborator
        if ($task->collaborators()->where('user_id', $invitee->id)->exists()) {
            $message = 'User sudah menjadi collaborator.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Set permissions based on role
        $permissions = $this->getPermissionsForRole($request->role);
        if ($request->has('can_edit')) $permissions['can_edit'] = $request->boolean('can_edit');
        if ($request->has('can_delete')) $permissions['can_delete'] = $request->boolean('can_delete');
        if ($request->has('can_invite')) $permissions['can_invite'] = $request->boolean('can_invite');
        
        // Create invitation
        $task->collaborators()->attach($invitee->id, array_merge($permissions, [
            'role' => $request->role,
            'invited_at' => now(),
        ]));
        
        $message = 'Undangan berhasil dikirim ke ' . $invitee->name;
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'collaborator' => $invitee,
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Accept collaboration invitation.
     */
    public function accept(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check if user has pending invitation
        $collaboration = $task->collaborators()
            ->where('user_id', $user->id)
            ->wherePivot('accepted_at', null)
            ->first();
        
        if (!$collaboration) {
            $message = 'Undangan tidak ditemukan.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Accept invitation
        $task->collaborators()->updateExistingPivot($user->id, [
            'accepted_at' => now(),
        ]);
        
        $message = 'Undangan berhasil diterima.';
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Decline collaboration invitation.
     */
    public function decline(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check if user has pending invitation
        $collaboration = $task->collaborators()
            ->where('user_id', $user->id)
            ->wherePivot('accepted_at', null)
            ->first();
        
        if (!$collaboration) {
            $message = 'Undangan tidak ditemukan.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Decline invitation (remove from collaborators)
        $task->collaborators()->detach($user->id);
        
        $message = 'Undangan ditolak.';
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Remove collaborator from task.
     */
    public function remove(Request $request, Task $task, User $user)
    {
        $this->authorize('update', $task);
        
        // Check if user is a collaborator
        if (!$task->collaborators()->where('user_id', $user->id)->exists()) {
            $message = 'User bukan collaborator.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Remove collaborator
        $task->collaborators()->detach($user->id);
        
        $message = 'Collaborator berhasil dihapus.';
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Update collaborator role.
     */
    public function updateRole(Request $request, Task $task, User $user)
    {
        $this->authorize('update', $task);
        
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:viewer,editor,admin',
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'can_invite' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Check if user is a collaborator
        if (!$task->collaborators()->where('user_id', $user->id)->exists()) {
            $message = 'User bukan collaborator.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Update role and permissions
        $permissions = $this->getPermissionsForRole($request->role);
        if ($request->has('can_edit')) $permissions['can_edit'] = $request->boolean('can_edit');
        if ($request->has('can_delete')) $permissions['can_delete'] = $request->boolean('can_delete');
        if ($request->has('can_invite')) $permissions['can_invite'] = $request->boolean('can_invite');
        
        $task->collaborators()->updateExistingPivot($user->id, array_merge($permissions, [
            'role' => $request->role,
        ]));
        
        $message = 'Peran collaborator berhasil diperbarui.';
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Get default permissions for a role.
     */
    private function getPermissionsForRole($role)
    {
        return match($role) {
            'viewer' => [
                'can_edit' => false,
                'can_delete' => false,
                'can_invite' => false,
            ],
            'editor' => [
                'can_edit' => true,
                'can_delete' => false,
                'can_invite' => false,
            ],
            'admin' => [
                'can_edit' => true,
                'can_delete' => true,
                'can_invite' => true,
            ],
            default => [
                'can_edit' => false,
                'can_delete' => false,
                'can_invite' => false,
            ],
        };
    }
    
    /**
     * Github webhook (placeholder).
     */
    public function githubWebhook(Request $request)
    {
        // TODO: Implement Github webhook integration
        return response()->json(['success' => true, 'message' => 'Webhook received']);
    }
    
    /**
     * Slack webhook (placeholder).
     */
    public function slackWebhook(Request $request)
    {
        // TODO: Implement Slack webhook integration
        return response()->json(['success' => true, 'message' => 'Webhook received']);
    }
}

