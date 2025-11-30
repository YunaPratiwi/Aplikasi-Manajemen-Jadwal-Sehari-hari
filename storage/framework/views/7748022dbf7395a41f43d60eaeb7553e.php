<?php ($title = 'Tasks Diarsipkan'); ?>


<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-semibold">Tasks Diarsipkan</h1>
    <a href="<?php echo e(route('tasks.index')); ?>" class="btn-secondary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<form method="GET" action="<?php echo e(route('archive.tasks')); ?>" class="card mb-6 p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Cari</label>
            <div class="input-group">
                <svg class="input-group-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-input" placeholder="Judul atau deskripsi">
            </div>
        </div>
        <div>
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select">
                <option value="">Semua</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php if(request('category') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn-secondary flex items-center gap-2" type="submit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Terapkan
            </button>
        </div>
    </div>
</form>

<div id="task-list" class="space-y-4">
    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="task-item priority-<?php echo e($task->priority_color); ?>" data-task-id="<?php echo e($task->id); ?>">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="priority-indicator priority-<?php echo e($task->priority_color); ?>"></span>
                        <a href="<?php echo e(route('tasks.show', $task)); ?>" class="font-medium hover:underline truncate"><?php echo e($task->title); ?></a>
                        <span class="badge badge-warning">Diarsipkan</span>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 ml-8">
<?php echo e($task->category?->name ?? '-'); ?> • Diarsipkan: <?php echo e($task->archived_at->format('d M Y H:i')); ?>

                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-8">
                        Dibuat: <?php echo e($task->created_at->format('d M Y H:i')); ?>

                    </div>
                </div>
                <div class="flex items-center gap-2 ml-4">
                    <form action="<?php echo e(route('tasks.unarchive', $task)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button class="btn-icon" type="submit" title="Kembalikan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </form>
                    <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" onsubmit="return confirm('Hapus task ini secara permanen?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn-icon" type="submit" title="Hapus Permanen">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium">Tidak ada task diarsipkan</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Tidak ada task yang diarsipkan saat ini.</p>
        </div>
    <?php endif; ?>
</div>

<div class="mt-6">
    <?php echo e($tasks->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\TODOLIST\TODOLIST\resources\views/tasks/archived.blade.php ENDPATH**/ ?>