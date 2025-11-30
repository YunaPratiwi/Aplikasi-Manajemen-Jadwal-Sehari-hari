@extends('layouts.app')
@php($title = 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Settings</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Manage your application preferences and settings</p>
        </div>
        <a href="{{ route('profile.show') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            View Profile
        </a>
    </div>

    <!-- Appearance Settings -->
    <div class="card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Appearance</h2>
        <div class="space-y-4">
            <div>
                <label class="form-label">Theme Preference</label>
                <select class="form-select" id="theme-select">
                    <option value="light" {{ auth()->user()->theme_preference === 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ auth()->user()->theme_preference === 'dark' ? 'selected' : '' }}>Dark</option>
                    <option value="auto" {{ auth()->user()->theme_preference === 'auto' ? 'selected' : '' }}>Auto (System)</option>
                </select>
                <p class="form-help">Choose how TodoList looks to you. Select Auto to match your system preferences.</p>
            </div>
            
            <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-800 dark:text-blue-300">Theme changes take effect immediately and are saved automatically.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Notifications</h2>
        <form action="{{ route('settings.notifications') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-4">
                <div class="flex items-start gap-3 p-4 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <input type="checkbox" name="email_notifications" class="form-checkbox mt-1" id="email-notif" 
                           {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                    <label for="email-notif" class="flex-1 cursor-pointer">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-slate-900 dark:text-white">Email Notifications</span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Receive email notifications for task reminders and updates</p>
                    </label>
                </div>
                
                <div class="flex items-start gap-3 p-4 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <input type="checkbox" name="push_notifications" class="form-checkbox mt-1" id="push-notif" 
                           {{ auth()->user()->push_notifications ? 'checked' : '' }}>
                    <label for="push-notif" class="flex-1 cursor-pointer">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="font-medium text-slate-900 dark:text-white">Push Notifications</span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Receive browser push notifications for real-time updates</p>
                    </label>
                </div>
            </div>
            
            @php
            $notificationSettings = auth()->user()->notification_settings ?? [];
            @endphp
            
            <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-3">Notification Types</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Task Reminders</span>
                        <input type="checkbox" name="notification_settings[task_reminders]" class="form-checkbox" 
                               {{ ($notificationSettings['task_reminders'] ?? true) ? 'checked' : '' }}>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Due Date Alerts</span>
                        <input type="checkbox" name="notification_settings[due_date_alerts]" class="form-checkbox" 
                               {{ ($notificationSettings['due_date_alerts'] ?? true) ? 'checked' : '' }}>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Collaboration Invites</span>
                        <input type="checkbox" name="notification_settings[collaboration_invites]" class="form-checkbox" 
                               {{ ($notificationSettings['collaboration_invites'] ?? true) ? 'checked' : '' }}>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Weekly Summary</span>
                        <input type="checkbox" name="notification_settings[weekly_summary]" class="form-checkbox" 
                               {{ ($notificationSettings['weekly_summary'] ?? true) ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="btn-primary">Save Preferences</button>
            </div>
        </form>
    </div>

    <!-- Data & Privacy -->
    <div class="card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Data & Privacy</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between py-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Export Your Data</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Download all your tasks, categories, and data in JSON format</p>
                    </div>
                </div>
                <a href="{{ route('export.tasks.custom') }}" class="btn-secondary btn-sm">Export</a>
            </div>
            
            <div class="flex items-center justify-between py-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Activity Log</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">View your recent account activity and changes</p>
                    </div>
                </div>
                <button class="btn-secondary btn-sm" disabled>Coming Soon</button>
            </div>
            
            <div class="flex items-center justify-between py-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Delete All Data</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Permanently delete all your tasks and categories (keeps account)</p>
                    </div>
                </div>
                <button onclick="confirmDeleteAllData()" class="btn-danger btn-sm">Delete Data</button>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts -->
    <div class="card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Keyboard Shortcuts</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                <span class="text-sm text-slate-700 dark:text-slate-300">Open Search</span>
                <kbd class="px-2 py-1 text-xs font-medium bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded shadow-sm">⌘ K</kbd>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                <span class="text-sm text-slate-700 dark:text-slate-300">Toggle Sidebar</span>
                <kbd class="px-2 py-1 text-xs font-medium bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded shadow-sm">⌘ B</kbd>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                <span class="text-sm text-slate-700 dark:text-slate-300">New Task</span>
                <kbd class="px-2 py-1 text-xs font-medium bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded shadow-sm">⌘ N</kbd>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                <span class="text-sm text-slate-700 dark:text-slate-300">Show Shortcuts</span>
                <kbd class="px-2 py-1 text-xs font-medium bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded shadow-sm">⌘ /</kbd>
            </div>
        </div>
    </div>

    <!-- About -->
    <div class="card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">About</h2>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">TodoList App</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Version 1.0.0</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Built with Laravel & TailwindCSS</p>
            </div>
        </div>
    </div>
</div>

<script>
// Theme switcher
document.getElementById('theme-select').addEventListener('change', function() {
    const theme = this.value;
    
    fetch('{{ route("settings.theme") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({ theme: theme })
    })
    .then(response => response.json())
    .then(data => {
        // Apply theme immediately
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else if (theme === 'light') {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            // Auto mode
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', prefersDark);
            localStorage.removeItem('theme');
        }
        
        // Show success notification
        if (window.showNotification) {
            window.showNotification('Theme updated successfully', 'success');
        }
    })
    .catch(error => {
        console.error('Error updating theme:', error);
    });
});

// Delete all data confirmation
function confirmDeleteAllData() {
    const confirmed = confirm('Are you sure you want to delete ALL your tasks and categories? This action cannot be undone.\n\nYour account will remain active but all data will be lost.');
    
    if (confirmed) {
        const doubleConfirm = confirm('This is your last chance. Are you ABSOLUTELY sure?');
        if (doubleConfirm) {
            // TODO: Implement delete all data endpoint
            alert('This feature will be implemented soon.');
        }
    }
}
</script>
@endsection