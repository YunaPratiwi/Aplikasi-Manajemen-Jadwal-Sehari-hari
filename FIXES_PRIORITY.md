# 🚨 PRIORITY FIXES - TODOLIST PROJECT

## 🔴 PRIORITY 0: CRITICAL (Fix Immediately)

### 1. Dashboard Controller - Data Missing ⚡

**File:** `app/Http/Controllers/DashboardController.php`

**Problem:** Dashboard view expects data yang tidak dikirim dari controller, akan menyebabkan "Undefined variable" error.

**Current Code:**
```php
public function index()
{
    $user = Auth::user();
    return view('dashboard.index', compact('user'));
}
```

**Fix Required:**
```php
public function index()
{
    $user = Auth::user();
    
    // Task statistics
    $totalTasks = $user->tasks()->active()->count();
    $completedTasks = $user->tasks()->completed()->count();
    $pendingTasks = $user->tasks()->pending()->count();
    $overdueTasks = $user->tasks()->overdue()->count();
    $tasksDueToday = $user->tasks()->dueToday()->count();
    
    // Recent tasks
    $recentTasks = $user->tasks()
        ->with('category')
        ->active()
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Categories count
    $categoriesCount = $user->categories()->active()->count();
    
    return view('dashboard.index', compact(
        'user',
        'totalTasks',
        'completedTasks',
        'pendingTasks',
        'overdueTasks',
        'tasksDueToday',
        'recentTasks',
        'categoriesCount'
    ));
}
```

**Estimated Time:** 30 minutes  
**Impact:** HIGH - Application currently broken

---

## 🔴 PRIORITY 1: HIGH (Fix This Week)

### 2. Task Actions Implementation 🔧

**File:** `app/Http/Controllers/TaskController.php`

**Problem:** Routes exists tapi methods tidak fully implemented.

**Methods to Implement:**

#### A. Toggle Task Completion
```php
public function toggle(Task $task)
{
    $this->authorize('update', $task);
    
    if ($task->is_completed) {
        $task->markAsPending();
        $message = 'Task marked as pending';
    } else {
        $task->markAsCompleted();
        $message = 'Task completed!';
    }
    
    return back()->with('success', $message);
}
```

#### B. Archive Task
```php
public function archive(Task $task)
{
    $this->authorize('delete', $task);
    
    $task->archive();
    
    return back()->with('success', 'Task archived successfully');
}
```

#### C. Unarchive Task
```php
public function unarchive(Task $task)
{
    $this->authorize('delete', $task);
    
    $task->unarchive();
    
    return back()->with('success', 'Task restored successfully');
}
```

#### D. Duplicate Task
```php
public function duplicate(Task $task)
{
    $this->authorize('view', $task);
    
    $newTask = $task->replicate();
    $newTask->title = $task->title . ' (Copy)';
    $newTask->is_completed = false;
    $newTask->completed_at = null;
    $newTask->save();
    
    // Copy tags if exist
    if ($task->tags) {
        $newTask->tags = $task->tags;
        $newTask->save();
    }
    
    return redirect()->route('tasks.edit', $newTask)
        ->with('success', 'Task duplicated successfully');
}
```

#### E. Bulk Actions
```php
public function bulkAction(Request $request)
{
    $validated = $request->validate([
        'action' => 'required|in:complete,archive,delete',
        'task_ids' => 'required|array',
        'task_ids.*' => 'exists:tasks,id'
    ]);
    
    $tasks = Task::whereIn('id', $validated['task_ids'])
        ->where('user_id', auth()->id())
        ->get();
    
    foreach ($tasks as $task) {
        switch ($validated['action']) {
            case 'complete':
                $task->markAsCompleted();
                break;
            case 'archive':
                $task->archive();
                break;
            case 'delete':
                $task->delete();
                break;
        }
    }
    
    return back()->with('success', count($tasks) . ' tasks updated');
}
```

**Estimated Time:** 4 hours  
**Impact:** HIGH - Core functionality

---

### 3. Create Missing Views 📄

#### A. Profile Views

**File:** `resources/views/profile/show.blade.php`
```blade
@php($title = 'Profile')
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Profile</h1>
        <p class="text-slate-600 dark:text-slate-400 mt-1">Manage your profile information</p>
    </div>

    <div class="grid gap-6">
        <!-- Profile Information -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold">Profile Information</h2>
                <a href="{{ route('profile.show') }}" class="btn-primary btn-sm">Edit Profile</a>
            </div>
            
            <div class="flex items-center gap-6 mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h3>
                    <p class="text-slate-600 dark:text-slate-400">{{ auth()->user()->email }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        @if(auth()->user()->email_verified_at)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-warning">Unverified</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Member Since</label>
                    <p class="text-slate-900 dark:text-white">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Last Login</label>
                    <p class="text-slate-900 dark:text-white">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Never' }}</p>
                </div>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="card">
            <h2 class="text-lg font-semibold mb-4">Your Statistics</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <p class="text-sm text-blue-600 dark:text-blue-400">Total Tasks</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ auth()->user()->tasks()->count() }}</p>
                </div>
                <div class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                    <p class="text-sm text-emerald-600 dark:text-emerald-400">Completed</p>
                    <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ auth()->user()->tasks()->completed()->count() }}</p>
                </div>
                <div class="p-4 rounded-lg bg-purple-50 dark:bg-purple-900/20">
                    <p class="text-sm text-purple-600 dark:text-purple-400">Categories</p>
                    <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ auth()->user()->categories()->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <h2 class="text-lg font-semibold mb-4">Change Password</h2>
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" required>
                        @error('current_password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="btn-primary">Update Password</button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="card border-red-200 dark:border-red-900">
            <h2 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-4">Danger Zone</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
            <button class="btn-danger">Delete Account</button>
        </div>
    </div>
</div>
@endsection
```

#### B. Settings Index

**File:** `resources/views/settings/index.blade.php`
```blade
@php($title = 'Settings')
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Settings</h1>
        <p class="text-slate-600 dark:text-slate-400 mt-1">Manage your application preferences</p>
    </div>

    <div class="grid gap-6">
        <!-- Theme Settings -->
        <div class="card">
            <h2 class="text-lg font-semibold mb-4">Appearance</h2>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Theme Preference</label>
                    <select class="form-select" id="theme-select">
                        <option value="light" {{ auth()->user()->theme_preference === 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ auth()->user()->theme_preference === 'dark' ? 'selected' : '' }}>Dark</option>
                        <option value="auto">Auto (System)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="card">
            <h2 class="text-lg font-semibold mb-4">Notifications</h2>
            <form action="{{ route('settings.notifications') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="form-checkbox-wrapper">
                        <input type="checkbox" name="email_notifications" class="form-checkbox" id="email-notif" 
                               {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                        <label for="email-notif" class="form-checkbox-label">
                            <span class="font-medium">Email Notifications</span>
                            <span class="block text-sm text-slate-500">Receive email notifications for task reminders</span>
                        </label>
                    </div>
                    
                    <div class="form-checkbox-wrapper">
                        <input type="checkbox" name="push_notifications" class="form-checkbox" id="push-notif" 
                               {{ auth()->user()->push_notifications ? 'checked' : '' }}>
                        <label for="push-notif" class="form-checkbox-label">
                            <span class="font-medium">Push Notifications</span>
                            <span class="block text-sm text-slate-500">Receive browser push notifications</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-primary">Save Preferences</button>
                </div>
            </form>
        </div>

        <!-- Data & Privacy -->
        <div class="card">
            <h2 class="text-lg font-semibold mb-4">Data & Privacy</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                    <div>
                        <p class="font-medium">Export Your Data</p>
                        <p class="text-sm text-slate-500">Download all your tasks and data</p>
                    </div>
                    <button class="btn-secondary btn-sm">Export</button>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="font-medium">Delete All Data</p>
                        <p class="text-sm text-slate-500">Permanently delete all your tasks</p>
                    </div>
                    <button class="btn-danger btn-sm">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('theme-select').addEventListener('change', function() {
    fetch('{{ route("settings.theme") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ theme: this.value })
    });
});
</script>
@endsection
```

#### C. Reusable Components

**File:** `resources/views/components/stat-card.blade.php`
```blade
@props(['title', 'value', 'icon', 'color' => 'blue', 'trend' => null])

<div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-{{ $color }}-500/10 to-transparent rounded-bl-full"></div>
    <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 flex items-center justify-center shadow-lg">
                {!! $icon !!}
            </div>
            @if($trend)
                <div class="flex items-center gap-1 text-{{ $color }}-600 dark:text-{{ $color }}-400 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span>{{ $trend }}</span>
                </div>
            @endif
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">{{ $title }}</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $value }}</p>
        {{ $slot }}
    </div>
</div>
```

**File:** `resources/views/components/empty-state.blade.php`
```blade
@props(['title', 'message', 'action' => null, 'actionUrl' => null, 'actionText' => null])

<div class="text-center py-12">
    <svg class="w-16 h-16 mx-auto mb-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
    </svg>
    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">{{ $title }}</h3>
    <p class="text-slate-500 dark:text-slate-400 mb-6">{{ $message }}</p>
    @if($action)
        {{ $action }}
    @elseif($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="btn-primary">{{ $actionText }}</a>
    @endif
</div>
```

**Estimated Time:** 6 hours  
**Impact:** HIGH - Complete user experience

---

## 🟡 PRIORITY 2: MEDIUM (Next 2 Weeks)

### 4. Task Comments System 💬

**Migration:** `database/migrations/xxxx_create_task_comments_table.php`
```php
Schema::create('task_comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('task_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->text('comment');
    $table->timestamps();
});
```

**Model:** `app/Models/TaskComment.php`
**Controller Method:** `TaskController@addComment`

### 5. Task Attachments Upload 📎

**Implementation Details:**
- File upload validation
- Storage in `storage/app/public/attachments`
- Database JSON field update
- Download & delete methods

### 6. Notification System 🔔

**Components:**
- NotificationController full implementation
- Real-time notifications with broadcasting
- Email notifications via queues
- Mark as read/unread functionality

**Estimated Time (4-6 combined):** 2 weeks  
**Impact:** MEDIUM - Enhanced user experience

---

## 🟢 PRIORITY 3: LOW (Future Enhancements)

### 7. Collaboration System Complete 👥
### 8. Advanced Reports & Analytics 📊
### 9. Export/Import Full Implementation 📤📥
### 10. Testing Suite ✅

---

## 📝 Implementation Order

**Week 1:**
1. Day 1: Fix Dashboard Controller
2. Day 2-3: Create Missing Views (Profile, Settings, Components)
3. Day 4-5: Implement Task Actions

**Week 2:**
4. Day 1-2: Task Comments
5. Day 3-4: Task Attachments
6. Day 5: Notification System Foundation

**Week 3:**
7. Notifications Complete
8. Testing & Bug Fixes
9. Performance Optimization

---

## ✅ Testing Checklist

After each fix:
- [ ] Manual testing in browser
- [ ] Check for console errors
- [ ] Test on mobile responsive
- [ ] Test dark mode
- [ ] Verify database changes
- [ ] Check error handling
- [ ] Verify authorization/permissions

---

## 🚀 Deployment Notes

Before deploying fixes to production:
1. Backup database
2. Test on staging environment
3. Run migrations
4. Clear caches
5. Test critical user flows
6. Monitor error logs

