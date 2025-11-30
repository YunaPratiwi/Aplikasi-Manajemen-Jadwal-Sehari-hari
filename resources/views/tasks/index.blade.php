@php($title = 'Tasks')
@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-semibold">Tasks</h1>
    <div class="flex items-center gap-3">
        <!-- Tombol kembali ke dashboard -->
        <a href="{{ route('dashboard') }}" class="btn-secondary">Kembali</a>


        <!-- Tombol tambah task -->
        <a href="{{ route('tasks.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Task
        </a>
    </div>
</div>


<form method="GET" action="{{ route('tasks.index') }}" class="card mb-6 p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
            <label class="form-label">Cari</label>
            <div class="input-group">
                <svg class="input-group-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Judul atau deskripsi">
            </div>
        </div>
        <div>
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select">
                <option value="">Semua</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Prioritas</label>
            <select name="priority" class="form-select">
                <option value="">Semua</option>
                @foreach(\App\Models\Task::getPriorityOptions() as $key => $label)
                    <option value="{{ $key }}" @selected(request('priority') == $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua</option>
                @foreach(\App\Models\Task::getStatusOptions() as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Due Date</label>
            <select name="due_date" class="form-select">
                <option value="">Semua</option>
                <option value="today" @selected(request('due_date') == 'today')>Hari ini</option>
                <option value="overdue" @selected(request('due_date') == 'overdue')>Terlambat</option>
                <option value="this_week" @selected(request('due_date') == 'this_week')>Minggu ini</option>
            </select>
        </div>
        <div class="lg:col-span-2 flex items-end gap-3">
            <label class="form-label">Urutkan</label>
            <select name="sort_by" class="form-select w-40">
                <option value="created_at" @selected(request('sort_by','created_at')=='created_at')>Dibuat</option>
                <option value="due_date" @selected(request('sort_by')=='due_date')>Jatuh Tempo</option>
                <option value="priority" @selected(request('sort_by')=='priority')>Prioritas</option>
            </select>
            <select name="sort_direction" class="form-select w-28">
                <option value="asc" @selected(request('sort_direction')=='asc')>Naik</option>
                <option value="desc" @selected(request('sort_direction','desc')=='desc')>Turun</option>
            </select>
            <button class="btn-secondary" type="submit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Terapkan
            </button>
        </div>
    </div>
</form>

<div id="task-list" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    @forelse($tasks as $task)
        <x-task-card :task="$task" />
    @empty
        <div class="col-span-full">
            <div class="card text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium">Tidak ada task ditemukan</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Buat task pertama Anda untuk memulai.</p>
                <div class="mt-6">
                    <a href="{{ route('tasks.create') }}" class="btn-primary">Buat Task Baru</a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $tasks->links() }}
    <div class="text-right mt-4">
        <form action="{{ route('tasks.reorder') }}" method="POST" id="reorder-form" class="hidden">
            @csrf
            <input type="hidden" name="task_ids" id="task-ids">
        </form>
    </div>
</div>
@endsection