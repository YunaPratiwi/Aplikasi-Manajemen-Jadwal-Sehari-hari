<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['task']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['task']); ?>
<?php foreach (array_filter((['task']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-slate-300 dark:hover:border-slate-600">
    <!-- Priority indicator -->
    <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b 
        <?php if($task->priority === 'high'): ?> from-red-500 to-red-600
        <?php elseif($task->priority === 'medium'): ?> from-amber-500 to-amber-600
        <?php else: ?> from-blue-500 to-blue-600
        <?php endif; ?>"></div>
    
    <!-- Task header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <form action="<?php echo e(route('tasks.toggle', $task)); ?>" method="POST" class="flex items-center">
                <?php echo csrf_field(); ?>
                <div class="relative">
                    <input type="checkbox" 
                           <?php echo e($task->is_completed ? 'checked' : ''); ?> 
                           class="peer h-5 w-5 rounded-lg border-2 border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200" 
                           onchange="this.form.submit()">
                    <svg class="absolute inset-0 w-5 h-5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </form>
            
            <div class="flex-1 min-w-0">
                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="block group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    <h3 class="font-semibold text-slate-900 dark:text-white truncate <?php echo e($task->is_completed ? 'line-through opacity-60' : ''); ?>">
                        <?php echo e($task->title); ?>

                    </h3>
                </a>
                <?php if($task->description): ?>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 line-clamp-2 <?php echo e($task->is_completed ? 'opacity-60' : ''); ?>">
                        <?php echo e(Str::limit($task->description, 100)); ?>

                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Priority badge -->
        <div class="flex items-center gap-2 ml-4">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                <?php if($task->priority === 'high'): ?> bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                <?php elseif($task->priority === 'medium'): ?> bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                <?php else: ?> bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                <?php endif; ?>">
                <?php if($task->priority === 'high'): ?>
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                <?php elseif($task->priority === 'medium'): ?>
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                <?php else: ?>
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                <?php endif; ?>
                <?php echo e(ucfirst($task->priority)); ?>

            </span>
        </div>
    </div>
    
    <!-- Task metadata -->
    <div class="flex items-center justify-between text-sm">
        <div class="flex items-center gap-4">
            <?php if($task->category): ?>
                <div class="flex items-center gap-1.5 text-slate-600 dark:text-slate-400">
                    <div class="w-2 h-2 rounded-full" style="background-color: <?php echo e($task->category?->color ?? '#6B7280'); ?>"></div>
                    <span><?php echo e($task->category?->name); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($task->due_date): ?>
                <div class="flex items-center gap-1.5 
                    <?php if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && !$task->is_completed): ?> text-red-600 dark:text-red-400
                    <?php elseif($task->due_date && \Carbon\Carbon::parse($task->due_date)->isToday()): ?> text-amber-600 dark:text-amber-400
                    <?php else: ?> text-slate-600 dark:text-slate-400
                    <?php endif; ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('M d, Y')); ?></span>
                    <?php if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && !$task->is_completed): ?>
                        <span class="text-xs font-medium px-1.5 py-0.5 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 rounded">Overdue</span>
                    <?php elseif($task->due_date && \Carbon\Carbon::parse($task->due_date)->isToday()): ?>
                        <span class="text-xs font-medium px-1.5 py-0.5 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 rounded">Today</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Status badge -->
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
            <?php if($task->status === 'completed'): ?> bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
            <?php elseif($task->status === 'in_progress'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
            <?php elseif($task->status === 'cancelled'): ?> bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
            <?php else: ?> bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300
            <?php endif; ?>">
            <?php if($task->status === 'completed'): ?>
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            <?php elseif($task->status === 'in_progress'): ?>
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            <?php endif; ?>
            <?php echo e(\App\Models\Task::getStatusOptions()[$task->status] ?? ucfirst($task->status)); ?>

        </span>
    </div>
    
    <!-- Action buttons (shown on hover) -->
    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        <div class="flex items-center gap-1">
            <a href="<?php echo e(route('tasks.edit', $task)); ?>" 
               class="p-1.5 rounded-lg bg-white dark:bg-slate-700 shadow-sm border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus task ini?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" 
                        class="p-1.5 rounded-lg bg-white dark:bg-slate-700 shadow-sm border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\TODOLIST (2)\TODOLIST\TODOLIST\resources\views/components/task-card.blade.php ENDPATH**/ ?>