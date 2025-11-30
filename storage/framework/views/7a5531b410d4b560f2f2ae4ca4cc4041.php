<?php ($title = 'Tambah Task'); ?>


<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Tambah Task</h1>
        <a href="<?php echo e(route('tasks.index')); ?>" class="btn-secondary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
    
    <form action="<?php echo e(route('tasks.store')); ?>" method="POST" class="card p-6">
        <?php echo csrf_field(); ?>
        
        <div class="space-y-5">
            <div>
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-input" value="<?php echo e(old('title')); ?>" required>
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-input" rows="4"><?php echo e(old('description')); ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php if(old('category_id')==$category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="form-label">Prioritas</label>
                    <select name="priority" class="form-select" required>
                        <?php $__currentLoopData = \App\Models\Task::getPriorityOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(old('priority')==$key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Jatuh Tempo</label>
                    <input type="date" name="due_date" class="form-input" value="<?php echo e(old('due_date')); ?>">
                </div>

                <div>
                    <label class="form-label">Pengingat</label>
                    <input type="datetime-local" name="reminder_at" class="form-input" value="<?php echo e(old('reminder_at')); ?>">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Tag (pisahkan dengan koma)</label>
                    <input type="text" name="tags" class="form-input" value="<?php echo e(old('tags')); ?>" placeholder="work, urgent">
                </div>

                <div>
                    <label class="form-label">Estimasi Menit</label>
                    <input type="number" name="estimated_minutes" class="form-input" value="<?php echo e(old('estimated_minutes')); ?>" min="1">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="<?php echo e(route('tasks.index')); ?>" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Task
                </button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\TODOLIST\TODOLIST\resources\views/tasks/create.blade.php ENDPATH**/ ?>