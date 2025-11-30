@php($title = 'Profile')
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Profile</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Manage your profile information and settings</p>
        </div>
        <a href="{{ route('settings.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Settings
        </a>
    </div>

    <!-- Profile Information -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Profile Information</h2>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="flex items-center gap-6 mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-7 h-7 bg-emerald-500 rounded-full border-4 border-white dark:border-slate-800"></div>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h3>
                    <p class="text-slate-600 dark:text-slate-400">{{ auth()->user()->email }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        @if(auth()->user()->email_verified_at)
                            <span class="badge badge-success">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Verified
                            </span>
                        @else
                            <span class="badge badge-warning">Unverified</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label form-label-required">Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="form-label form-label-required">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-input" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="form-help">We'll never share your email with anyone else.</p>
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
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Account Statistics -->
    <div class="card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Your Statistics</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Tasks</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">{{ auth()->user()->tasks()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">Completed</p>
                        <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">{{ auth()->user()->tasks()->completed()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="p-4 rounded-lg bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Categories</p>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300 mt-1">{{ auth()->user()->categories()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Change Password</h2>
        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="form-label form-label-required">Current Password</label>
                <input type="password" name="current_password" class="form-input" required autocomplete="current-password">
                @error('current_password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="form-label form-label-required">New Password</label>
                <input type="password" name="password" class="form-input" required autocomplete="new-password">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="form-help">Must be at least 8 characters with mixed case, numbers, and symbols.</p>
            </div>
            
            <div>
                <label class="form-label form-label-required">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password">
            </div>
            
            <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="btn-primary">Update Password</button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="card border-2 border-red-200 dark:border-red-900/50">
        <h2 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-4">Danger Zone</h2>
        <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">Delete Your Account</p>
                    <p class="text-sm text-red-700 dark:text-red-400 mt-1">Once you delete your account, there is no going back. Please be certain. All your tasks, categories, and data will be permanently deleted.</p>
                </div>
            </div>
        </div>
        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone. Type DELETE to confirm.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Account
            </button>
        </form>
    </div>
</div>
@endsection
