@php($title = 'Detail Kategori')
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
  <!-- Header Section -->
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-600 p-8 shadow-xl">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="relative z-10">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-4 mb-4 md:mb-0">
          <div class="relative">
            <div class="w-16 h-16 rounded-2xl shadow-lg" style="background-color: {{ $category->color }}"></div>
            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center border-2 border-white dark:border-slate-800 shadow-lg">
              <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
          </div>
          <div>
            <h1 class="text-3xl font-bold text-white mb-1">{{ $category->name }}</h1>
            <div class="flex items-center gap-3">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                    :class="$category->is_active ? 'bg-white/20 text-white' : 'bg-white/10 text-white/80'">
                {{ $category->is_active ? 'Aktif' : 'Non-aktif' }}
              </span>
              <span class="text-white/80 text-sm">{{ $category->formatted_created_at ?? 'Dibuat pada tanggal tidak diketahui' }}</span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-200 border border-white/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
          </a>
          <form action="{{ route('categories.toggleActive', $category) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-200 border border-white/20">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($category->is_active)
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                @else
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                @endif
              </svg>
              {{ $category->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
            </button>
          </form>
          <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Category Details -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-8">
      <!-- Category Information -->
      <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-8 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-cyan-500/10 to-transparent rounded-bl-full"></div>
        <div class="relative z-10">
          <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Informasi Kategori</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Deskripsi</h3>
              <p class="text-slate-900 dark:text-white">{{ $category->description ?: 'Tidak ada deskripsi tersedia' }}</p>
            </div>
            
            <div>
              <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Warna</h3>
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg shadow-md" style="background-color: {{ $category->color }}"></div>
                <div>
                  <p class="font-medium text-slate-900 dark:text-white">{{ $category->color }}</p>
                  <p class="text-sm text-slate-500 dark:text-slate-400">HEX Color Code</p>
                </div>
              </div>
            </div>
            
            <div>
              <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Status</h3>
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full" :class="$category->is_active ? 'bg-emerald-500' : 'bg-slate-400'"></div>
                <span class="font-medium text-slate-900 dark:text-white">{{ $category->is_active ? 'Aktif' : 'Non-aktif' }}</span>
              </div>
            </div>
            
            <div>
              <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Icon</h3>
              <p class="font-medium text-slate-900 dark:text-white">{{ $category->icon ?: 'folder' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-8 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
        <div class="relative z-10">
          <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Statistik Kategori</h2>
          
          @php($stats = $stats ?? $category->getStats())
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200/60 dark:border-blue-800/30">
              <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_tasks'] ?? ($category->tasks()->count()) }}</h3>
              <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Total Tugas</p>
            </div>
            
            <div class="text-center p-6 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200/60 dark:border-emerald-800/30">
              <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['completed_tasks'] ?? ($category->tasks()->where('is_completed', true)->count()) }}</h3>
              <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Tugas Selesai</p>
            </div>
            
            <div class="text-center p-6 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200/60 dark:border-amber-800/30">
              <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['pending_tasks'] ?? ($category->tasks()->where('is_completed', false)->count()) }}</h3>
              <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Tugas Pending</p>
            </div>
          </div>
          
          <!-- Progress Bar -->
          <div class="mt-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Progress Penyelesaian</span>
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                {{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0 }}%
              </span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
              <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-3 rounded-full transition-all duration-500" 
                   style="width: {{ $stats['total_tasks'] > 0 ? ($stats['completed_tasks'] / $stats['total_tasks']) * 100 : 0 }}%"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tasks List -->
      <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-8 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full"></div>
        <div class="relative z-10">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Tugas dalam Kategori</h2>
            <a href="{{ route('tasks.create', ['category_id' => $category->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Tambah Tugas
            </a>
          </div>
          
          @if($category->tasks->count() > 0)
            <div class="space-y-3">
              @foreach($category->tasks->take(10) as $task)
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                  <div class="flex items-center gap-3">
                    <input type="checkbox" {{ $task->is_completed ? 'checked' : '' }} disabled class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    <div>
                      <p class="font-medium text-slate-900 dark:text-white {{ $task->is_completed ? 'line-through opacity-60' : '' }}">{{ $task->title }}</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" 
                              :class="[
                                $task->priority === 'high' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' : '',
                                $task->priority === 'medium' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' : '',
                                $task->priority === 'low' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : ''
                              ]">
                          {{ ucfirst($task->priority) }}
                        </span>
                        @if($task->due_date)
                          <span class="text-xs text-slate-500 dark:text-slate-400">{{ $task->formatted_due_date }}</span>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
            
            @if($category->tasks->count() > 10)
              <div class="mt-4 text-center">
                <a href="{{ route('tasks.index', ['category' => $category->id]) }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 font-medium">
                  Lihat semua {{ $category->tasks->count() }} tugas
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </a>
              </div>
            @endif
          @else
            <div class="text-center py-8">
              <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
              </div>
              <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Belum ada tugas</h3>
              <p class="text-slate-600 dark:text-slate-400 mb-4">Mulai dengan menambahkan tugas pertama untuk kategori ini</p>
              <a href="{{ route('tasks.create', ['category_id' => $category->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Tugas
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
      <!-- Quick Actions -->
      <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full"></div>
        <div class="relative z-10">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Aksi Cepat</h3>
          <div class="space-y-3">
            <a href="{{ route('tasks.create', ['category_id' => $category->id]) }}" class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 hover:from-blue-100 dark:hover:from-blue-900/30 transition-colors group">
              <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium text-slate-900 dark:text-white">Tambah Tugas</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Buat tugas baru</p>
              </div>
            </a>
            
            <a href="{{ route('categories.edit', $category) }}" class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 hover:from-emerald-100 dark:hover:from-emerald-900/30 transition-colors group">
              <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium text-slate-900 dark:text-white">Edit Kategori</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Ubah informasi</p>
              </div>
            </a>
            
            <a href="{{ route('tasks.index', ['category' => $category->id]) }}" class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 hover:from-purple-100 dark:hover:from-purple-900/30 transition-colors group">
              <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium text-slate-900 dark:text-white">Lihat Semua Tugas</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Kelola tugas</p>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/10 to-transparent rounded-bl-full"></div>
        <div class="relative z-10">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
          <div class="space-y-3">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-slate-900 dark:text-white">Kategori dibuat</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $category->formatted_created_at ?? 'Tidak diketahui' }}</p>
              </div>
            </div>
            
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-slate-900 dark:text-white">Kategori diperbarui</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $category->formatted_updated_at ?? 'Tidak diketahui' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Related Categories -->
      @if(isset($relatedCategories) && $relatedCategories->count() > 0)
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
          <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/10 to-transparent rounded-bl-full"></div>
          <div class="relative z-10">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Kategori Terkait</h3>
            <div class="space-y-3">
              @foreach($relatedCategories->take(5) as $relatedCategory)
                <a href="{{ route('categories.show', $relatedCategory) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                  <div class="w-8 h-8 rounded-lg" style="background-color: {{ $relatedCategory->color }}"></div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $relatedCategory->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $relatedCategory->tasks_count ?? 0 }} tugas</p>
                  </div>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection