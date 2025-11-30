<?php ($title = 'Tasks'); ?>


<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-semibold">Tasks</h1>
    <div class="flex items-center gap-3">
        <!-- Tombol kembali ke dashboard -->
        <a href="<?php echo e(route('dashboard')); ?>" class="btn-secondary">Kembali</a>


        <!-- Tombol tambah task -->
        <a href="<?php echo e(route('tasks.create')); ?>" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Task
        </a>
    </div>
</div>


<form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="card mb-6 p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
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
        <div>
            <label class="form-label">Prioritas</label>
            <select name="priority" class="form-select">
                <option value="">Semua</option>
                <?php $__currentLoopData = \App\Models\Task::getPriorityOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php if(request('priority') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua</option>
                <?php $__currentLoopData = \App\Models\Task::getStatusOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php if(request('status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Due Date</label>
            <select name="due_date" class="form-select">
                <option value="">Semua</option>
                <option value="today" <?php if(request('due_date') == 'today'): echo 'selected'; endif; ?>>Hari ini</option>
                <option value="overdue" <?php if(request('due_date') == 'overdue'): echo 'selected'; endif; ?>>Terlambat</option>
                <option value="this_week" <?php if(request('due_date') == 'this_week'): echo 'selected'; endif; ?>>Minggu ini</option>
            </select>
        </div>
        <div class="lg:col-span-2 flex items-end gap-3">
            <label class="form-label">Urutkan</label>
            <select name="sort_by" class="form-select w-40">
                <option value="created_at" <?php if(request('sort_by','created_at')=='created_at'): echo 'selected'; endif; ?>>Dibuat</option>
                <option value="due_date" <?php if(request('sort_by')=='due_date'): echo 'selected'; endif; ?>>Jatuh Tempo</option>
                <option value="priority" <?php if(request('sort_by')=='priority'): echo 'selected'; endif; ?>>Prioritas</option>
            </select>
            <select name="sort_direction" class="form-select w-28">
                <option value="asc" <?php if(request('sort_direction')=='asc'): echo 'selected'; endif; ?>>Naik</option>
                <option value="desc" <?php if(request('sort_direction','desc')=='desc'): echo 'selected'; endif; ?>>Turun</option>
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
    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php if (isset($component)) { $__componentOriginal5c7e45c1b38a85fb63a7b75e56a24d35 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c7e45c1b38a85fb63a7b75e56a24d35 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.task-card','data' => ['task' => $task]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('task-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['task' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($task)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c7e45c1b38a85fb63a7b75e56a24d35)): ?>
<?php $attributes = $__attributesOriginal5c7e45c1b38a85fb63a7b75e56a24d35; ?>
<?php unset($__attributesOriginal5c7e45c1b38a85fb63a7b75e56a24d35); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c7e45c1b38a85fb63a7b75e56a24d35)): ?>
<?php $component = $__componentOriginal5c7e45c1b38a85fb63a7b75e56a24d35; ?>
<?php unset($__componentOriginal5c7e45c1b38a85fb63a7b75e56a24d35); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full">
            <div class="card text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium">Tidak ada task ditemukan</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Buat task pertama Anda untuk memulai.</p>
                <div class="mt-6">
                    <a href="<?php echo e(route('tasks.create')); ?>" class="btn-primary">Buat Task Baru</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="mt-6">
    <?php echo e($tasks->links()); ?>

    <div class="text-right mt-4">
        <form action="<?php echo e(route('tasks.reorder')); ?>" method="POST" id="reorder-form" class="hidden">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="task_ids" id="task-ids">
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\TODOLIST (2)\TODOLIST\TODOLIST\resources\views/tasks/index.blade.php ENDPATH**/ ?>