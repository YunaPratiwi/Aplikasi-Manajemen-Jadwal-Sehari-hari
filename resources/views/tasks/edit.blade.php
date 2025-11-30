@php($title = 'Edit Task')
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Edit Task</h1>
        <a href="{{ route('dashboard') }}" class="btn-secondary">Kembali</a>
    </div>
    
    <form action="{{ route('tasks.update', $task) }}" method="POST" class="card p-6">
        @csrf @method('PUT')
        
        <div class="space-y-5">
            <div>
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-input" value="{{ old('title', $task->title) }}" required>
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-input" rows="4">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $task->category_id)==$category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Prioritas</label>
                    <select name="priority" class="form-select" required>
                        @foreach(\App\Models\Task::getPriorityOptions() as $key => $label)
                            <option value="{{ $key }}" @selected(old('priority', $task->priority)==$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach(\App\Models\Task::getStatusOptions() as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $task->status)==$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Jatuh Tempo</label>
                    <input type="date" name="due_date" class="form-input" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}">
                </div>

                <div>
                    <label class="form-label">Pengingat</label>
                    <input type="datetime-local" name="reminder_at" class="form-input" value="{{ old('reminder_at', optional($task->reminder_at)->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Tag (pisahkan dengan koma)</label>
                    <input type="text" name="tags" class="form-input" value="{{ old('tags', implode(',', $task->tags ?? [])) }}" placeholder="work, urgent">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Estimasi Menit</label>
                        <input type="number" name="estimated_minutes" class="form-input" value="{{ old('estimated_minutes', $task->estimated_minutes) }}" min="1">
                    </div>

                    <div>
                        <label class="form-label">Aktual Menit</label>
                        <input type="number" name="actual_minutes" class="form-input" value="{{ old('actual_minutes', $task->actual_minutes) }}" min="1">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('tasks.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection