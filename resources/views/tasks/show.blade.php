@php($title = 'Detail Task')
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Detail Task</h1>
        <a href="{{ route('tasks.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="card p-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold">{{ $task->title }}</h2>
                <div class="flex items-center flex-wrap gap-2 mt-2">
                    <span class="priority-indicator priority-{{ $task->priority_color }}"></span>
                    <span class="badge">{{ \App\Models\Task::getStatusOptions()[$task->status] ?? ucfirst($task->status) }}</span>

                    @php
                        $due = $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                    @endphp

                    @if($due && $due->isPast() && !in_array($task->status, [\App\Models\Task::STATUS_COMPLETED, \App\Models\Task::STATUS_CANCELLED]))
                        <span class="badge badge-danger">Terlambat</span>
                    @elseif($due && $due->isToday() && !in_array($task->status, [\App\Models\Task::STATUS_COMPLETED, \App\Models\Task::STATUS_CANCELLED]))
                        <span class="badge badge-warning">Hari ini</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Toggle Complete --}}
                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                    @csrf
                    <button class="btn-secondary flex items-center gap-2" type="submit">
                        @if($task->is_completed)
                            <x-heroicon-o-check-circle class="w-4 h-4" />
                            Uncomplete
                        @else
                            <x-heroicon-o-check class="w-4 h-4" />
                            Complete
                        @endif
                    </button>
                </form>

                {{-- Edit --}}
                <a href="{{ route('tasks.edit', $task) }}" class="btn-primary flex items-center gap-2">
                    <x-heroicon-o-pencil class="w-4 h-4" />
                    Edit
                </a>
            </div>
        </div>

        {{-- Task Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <x-task-info label="Kategori" :value="$task->category?->name ?? '-'" />
            <x-task-info label="Prioritas" :value="ucfirst($task->priority)" />
            <x-task-info label="Jatuh Tempo" :value="$task->formatted_due_date ?? '-'" />
            <x-task-info label="Pengingat" :value="$task->reminder_at ? \Carbon\Carbon::parse($task->reminder_at)->format('d M Y H:i') : '-'" />
        </div>

        {{-- Deskripsi --}}
        <div class="mb-6">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Deskripsi</p>
            <div class="prose dark:prose-invert max-w-none">
                @if($task->description)
                    <p>{{ $task->description }}</p>
                @else
                    <p class="text-gray-500 italic">Tidak ada deskripsi.</p>
                @endif
            </div>
        </div>

        {{-- Time & Created --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <x-task-info label="Estimasi" :value="$task->estimated_minutes ? $task->estimated_minutes . ' menit' : '-'" />
            <x-task-info label="Aktual" :value="$task->actual_minutes ? $task->actual_minutes . ' menit' : '-'" />
            <x-task-info label="Dibuat" :value="$task->created_at->format('d M Y H:i')" />
        </div>

        {{-- Tags --}}
        @if(!empty($task->tags))
            <div class="mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Tags</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($task->tags as $tag)
                        <span class="badge">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Attachments --}}
        @if(!empty($task->attachments))
            <div class="mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Attachments</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($task->attachments as $path)
                        <li>
                            <a href="{{ asset('storage/' . $path) }}" class="text-primary-600 hover:underline" target="_blank">
                                {{ basename($path) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Collaborators --}}
        @if($task->collaborators && $task->collaborators->count())
            <div class="mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Kolaborator</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($task->collaborators as $collab)
                        <span class="badge">{{ $collab->user->email }} ({{ ucfirst($collab->role) }})</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Footer Actions --}}
        <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            {{-- Archive --}}
            <form action="{{ route('tasks.archive', $task) }}" method="POST">
                @csrf
                <button class="btn-secondary flex items-center gap-2" type="submit">
                    <x-heroicon-o-archive class="w-4 h-4" />
                    Arsipkan
                </button>
            </form>

            {{-- Delete --}}
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Hapus task ini?')">
                @csrf
                @method('DELETE')
                <button class="btn-danger flex items-center gap-2" type="submit">
                    <x-heroicon-o-trash class="w-4 h-4" />
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
