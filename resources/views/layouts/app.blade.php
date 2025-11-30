<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ 
    dark: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: false,
    searchOpen: false,
    notifications: @json(session()->get('notifications', [])),
    loading: false,
    searchQuery: '',
    keyboardShortcuts: false,
    scrolled: false
}" x-init="
    $watch('dark', val => { 
        localStorage.setItem('theme', val ? 'dark' : 'light'); 
        document.documentElement.classList.toggle('dark', val); 
    });
    $watch('sidebarOpen', val => {
        document.body.classList.toggle('overflow-hidden', val);
    });
    // Handle scroll for header styling
    window.addEventListener('scroll', () => {
        scrolled = window.scrollY > 10;
    });
    // Initialize notifications
    @if(session()->has('success'))
        notifications.push({ id: Date.now(), type: 'success', message: '{{ session('success') }}' });
    @endif
    @if(session()->has('error'))
        notifications.push({ id: Date.now(), type: 'error', message: '{{ session('error') }}' });
    @endif
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchOpen = true;
            $nextTick(() => $refs.searchInput.focus());
        }
        // Ctrl/Cmd + B for sidebar
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            sidebarOpen = !sidebarOpen;
        }
        // Ctrl/Cmd + / for keyboard shortcuts help
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            keyboardShortcuts = true;
        }
        // Escape to close modals
        if (e.key === 'Escape') {
            searchOpen = false;
            keyboardShortcuts = false;
        }
    });
">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="description" content="{{ $description ?? 'Kelola tugas Anda dengan efisien menggunakan TodoList' }}">
    <meta name="keywords" content="todo, tugas, produktivitas, manajemen">
    <meta name="author" content="TodoList">
    <meta property="og:title" content="{{ $title ?? 'TODOLIST' }}">
    <meta property="og:description" content="{{ $description ?? 'Kelola tugas Anda dengan efisien menggunakan TodoList' }}">
    <meta property="og:type" content="website">
    <title>{{ $title ?? 'TODOLIST' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <div class="min-h-full flex">
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition-all duration-300 ease-out"
               x-transition:enter-start="-translate-x-full opacity-0"
               x-transition:enter-end="translate-x-0 opacity-100"
               x-transition:leave="transition-all duration-200 ease-in"
               x-transition:leave-start="translate-x-0 opacity-100"
               x-transition:leave-end="-translate-x-full opacity-0"
               @click.away="sidebarOpen = false"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl shadow-2xl lg:hidden lg:static lg:inset-0 lg:translate-x-0 lg:z-auto lg:shadow-none lg:bg-transparent lg:backdrop-blur-none"
               :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200/60 dark:border-slate-700/60">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl blur-sm opacity-75 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">TodoList</span>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 text-blue-700 dark:text-blue-300 shadow-sm' : 'text-slate-700 hover:text-slate-900 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <div x-show="{{ request()->routeIs('dashboard') }}" class="absolute -inset-1 bg-blue-500 rounded-lg opacity-20 blur-sm"></div>
                        </div>
                        <span>Dashboard</span>
                        <span x-show="{{ request()->routeIs('dashboard') }}" class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></span>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('tasks.*') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 text-blue-700 dark:text-blue-300 shadow-sm' : 'text-slate-700 hover:text-slate-900 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <div x-show="{{ request()->routeIs('tasks.*') }}" class="absolute -inset-1 bg-blue-500 rounded-lg opacity-20 blur-sm"></div>
                        </div>
                        <span>Tasks</span>
                        <span x-show="{{ request()->routeIs('tasks.*') }}" class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('categories.*') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 text-blue-700 dark:text-blue-300 shadow-sm' : 'text-slate-700 hover:text-slate-900 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <div x-show="{{ request()->routeIs('categories.*') }}" class="absolute -inset-1 bg-blue-500 rounded-lg opacity-20 blur-sm"></div>
                        </div>
                        <span>Categories</span>
                        <span x-show="{{ request()->routeIs('categories.*') }}" class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></span>
                    </a>
                    <a href="{{ route('archive.tasks') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('archive.*') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 text-blue-700 dark:text-blue-300 shadow-sm' : 'text-slate-700 hover:text-slate-900 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            <div x-show="{{ request()->routeIs('archive.*') }}" class="absolute -inset-1 bg-blue-500 rounded-lg opacity-20 blur-sm"></div>
                        </div>
                        <span>Archive</span>
                        <span x-show="{{ request()->routeIs('archive.*') }}" class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></span>
                    </a>
                    
                    <div class="pt-6 mt-6 border-t border-slate-200/60 dark:border-slate-700/60">
                        <h3 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider dark:text-slate-400">Settings</h3>
                        <a href="{{ route('profile.show') }}" class="group flex items-center gap-3 px-4 py-3 mt-3 rounded-xl text-sm font-medium text-slate-700 hover:text-slate-900 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Profile</span>
                        </a>
                        <a href="{{ route('settings.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-700 hover:text-slate-900 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                            <span>Settings</span>
                        </a>
                    </div>
                </nav>
                
                <!-- Sidebar Footer -->
                <div class="p-6 border-t border-slate-200/60 dark:border-slate-700/60">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium shadow-lg">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-slate-900"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-0">
            <!-- Top Navigation -->
            <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-700/60 sticky top-0 z-40 transition-all duration-200" 
                    :class="{ 'shadow-lg': scrolled, 'shadow-sm': !scrolled }">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Mobile menu button -->
                            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            
                            <!-- Search -->
                            <div class="relative">
                                <button @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" class="group flex items-center gap-2 px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-800 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span class="hidden md:inline text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Search tasks...</span>
                                    <span class="hidden md:inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 dark:group-hover:bg-slate-600 transition-colors">⌘K</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Dark mode toggle -->
                            <button @click="dark = !dark" class="group relative p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow-md" title="Toggle dark mode">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <svg x-show="!dark" class="relative w-5 h-5 text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg x-show="dark" class="relative w-5 h-5 text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </button>
                            
                            <!-- Notifications -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="group relative p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow-md" title="Notifications">
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                    <svg class="relative w-5 h-5 text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                                </button>
                                
                                <!-- Notifications Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 mt-3 w-80 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl py-2 z-20 border border-slate-200/60 dark:border-slate-700/60 backdrop-blur-xl"
                                     style="display: none;">
                                    <div class="px-4 py-3 border-b border-slate-200/60 dark:border-slate-700/60">
                                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Notifications</h3>
                                    </div>
                                    <div class="max-h-80 overflow-y-auto">
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <div class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-2 h-2 rounded-full mt-2" 
                                                             :class="{
                                                                 'bg-emerald-500': notification.type === 'success',
                                                                 'bg-red-500': notification.type === 'error',
                                                                 'bg-amber-500': notification.type === 'warning',
                                                                 'bg-blue-500': notification.type === 'info'
                                                             }"></div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm text-slate-900 dark:text-white" x-text="notification.message"></p>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Just now</p>
                                                    </div>
                                                    <button @click="notifications = notifications.filter(n => n.id !== notification.id)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                        <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                            No notifications
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 border-t border-slate-200/60 dark:border-slate-700/60">
                                        <a href="#" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">View all notifications</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- User Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="group flex items-center gap-2 p-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden md:inline text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4 hidden md:inline text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Profile Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl py-2 z-20 border border-slate-200/60 dark:border-slate-700/60 backdrop-blur-xl"
                                     style="display: none;">
                                    <div class="px-4 py-3 border-b border-slate-200/60 dark:border-slate-700/60">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profile
                                    </a>
                                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        </svg>
                                        Settings
                                    </a>
                                    <div class="border-t border-slate-200/60 dark:border-slate-700/60 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Header -->
            @isset($header)
                <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm border-b border-slate-200/60 dark:border-slate-700/60">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Breadcrumbs -->
            @isset($breadcrumbs)
                <div class="bg-white/40 dark:bg-slate-900/40 backdrop-blur-sm border-b border-slate-200/40 dark:border-slate-700/40">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="flex items-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-colors">
                                        <svg class="flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        <span class="sr-only">Home</span>
                                    </a>
                                </li>
                                @foreach($breadcrumbs as $index => $breadcrumb)
                                    <li>
                                        <div class="flex items-center">
                                            <svg class="flex-shrink-0 h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            @if($index === count($breadcrumbs) - 1)
                                                <span class="ml-2 font-medium text-slate-900 dark:text-white">{{ $breadcrumb['title'] }}</span>
                                            @else
                                                <a href="{{ $breadcrumb['url'] ?? '#' }}" class="ml-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-colors">{{ $breadcrumb['title'] }}</a>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
            @endisset

            <!-- Main Content -->
            <main class="flex-1 mx-auto max-w-7xl py-8 px-4 sm:px-6 lg:px-8">
                <!-- Notifications -->
                <div class="fixed top-20 right-4 z-50 space-y-3" x-data="{ notifications: [] }">
                    <template x-for="notification in notifications" :key="notification.id">
                        <div x-show="notification.show" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-full"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform translate-x-full"
                             class="notification max-w-sm w-full bg-white dark:bg-slate-900 backdrop-blur-xl shadow-2xl rounded-2xl pointer-events-auto ring-1 ring-slate-200/60 dark:ring-slate-700/60 overflow-hidden"
                             :class="{
                                 'border-l-4 border-emerald-500': notification.type === 'success',
                                 'border-l-4 border-red-500': notification.type === 'error',
                                 'border-l-4 border-amber-500': notification.type === 'warning',
                                 'border-l-4 border-blue-500': notification.type === 'info'
                             }">
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg x-show="notification.type === 'success'" class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg x-show="notification.type === 'error'" class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg x-show="notification.type === 'warning'" class="h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg x-show="notification.type === 'info'" class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white" x-text="notification.title"></p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400" x-text="notification.message"></p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex">
                                        <button @click="notification.show = false" class="inline-flex text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-900 transition-colors">
                                            <span class="sr-only">Close</span>
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Loading Overlay -->
                <div x-show="loading" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-4 sm:p-6">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">
                                            Processing...
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                                Please wait while we process your request.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm border-t border-slate-200/60 dark:border-slate-700/60">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            &copy; {{ date('Y') }} TodoList. All rights reserved.
                        </div>
                        <div class="flex items-center space-x-6 mt-4 md:mt-0">
                            <a href="#" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-colors">Privacy Policy</a>
                            <a href="#" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-colors">Terms of Service</a>
                            <button @click="keyboardShortcuts = true" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-colors">Keyboard Shortcuts</button>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Search Modal -->
    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="searchOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-start justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="searchOpen" @click="searchOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="searchOpen" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-4 sm:p-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input x-ref="searchInput" x-model="searchQuery" type="text" class="form-input pl-12 pr-12 py-4 w-full text-lg rounded-xl border-slate-200 dark:border-slate-700 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400/20" placeholder="Search for tasks, categories, or settings...">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">ESC</kbd>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider dark:text-slate-400 mb-3">Recent searches</h3>
                        <div class="space-y-1">
                            <a href="#" class="block px-4 py-3 rounded-xl text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-all">Complete project documentation</a>
                            <a href="#" class="block px-4 py-3 rounded-xl text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-all">Review pull requests</a>
                            <a href="#" class="block px-4 py-3 rounded-xl text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-all">Update team meeting notes</a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider dark:text-slate-400 mb-3">Quick actions</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('tasks.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">New Task</span>
                            </a>
                            <a href="{{ route('categories.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">New Category</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts Modal -->
    <div x-show="keyboardShortcuts" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="keyboardShortcuts = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-start justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="keyboardShortcuts" @click="keyboardShortcuts = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="keyboardShortcuts" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white" id="modal-title">
                                Keyboard Shortcuts
                            </h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Search</span>
                                    <div class="flex items-center gap-1">
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">Ctrl</kbd>
                                        <span class="text-slate-400 dark:text-slate-500">+</span>
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">K</kbd>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Toggle Sidebar</span>
                                    <div class="flex items-center gap-1">
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">Ctrl</kbd>
                                        <span class="text-slate-400 dark:text-slate-500">+</span>
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">B</kbd>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Toggle Dark Mode</span>
                                    <div class="flex items-center gap-1">
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">Ctrl</kbd>
                                        <span class="text-slate-400 dark:text-slate-500">+</span>
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">D</kbd>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Keyboard Shortcuts</span>
                                    <div class="flex items-center gap-1">
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">Ctrl</kbd>
                                        <span class="text-slate-400 dark:text-slate-500">+</span>
                                        <kbd class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">/</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="keyboardShortcuts = false" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-base font-medium text-white hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    @stack('modals')
</body>
</html>