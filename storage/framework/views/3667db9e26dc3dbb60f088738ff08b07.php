<?php ($title = 'Dashboard'); ?>


<?php $__env->startSection('content'); ?>
<div class="space-y-8">
  <!-- Welcome Section -->
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 p-8 shadow-xl">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="relative z-10">
      <h1 class="text-3xl font-bold text-white mb-2">Selamat datang kembali, <?php echo e($user->name); ?>!</h1>
      <p class="text-white/90 text-lg">Berikut adalah ringkasan aktivitas dan statistik Anda hari ini.</p>
      <div class="mt-6 flex items-center gap-4">
        <div class="flex items-center gap-2 text-white/80 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          <?php echo e(now()->format('l, d F Y')); ?>

        </div>
        <div class="flex items-center gap-2 text-white/80 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <?php echo e(now()->format('H:i')); ?>

        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
          </div>
          <div class="flex items-center gap-1 text-blue-600 dark:text-blue-400 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            <span>12%</span>
          </div>
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Tasks</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($totalTasks); ?></p>
        <div class="mt-4 w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
          <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 65%"></div>
        </div>
      </div>
    </div>
    
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            <span>8%</span>
          </div>
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Completed</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($completedTasks); ?></p>
        <div class="mt-4 w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
          <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full" style="width: <?php echo e($totalTasks > 0 ? ($completedTasks / $totalTasks * 100) : 0); ?>%"></div>
        </div>
      </div>
    </div>
    
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/10 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex items-center gap-1 text-amber-600 dark:text-amber-400 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
            </svg>
            <span>5%</span>
          </div>
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Pending</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($pendingTasks); ?></p>
        <div class="mt-4 w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
          <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2 rounded-full" style="width: <?php echo e($totalTasks > 0 ? ($pendingTasks / $totalTasks * 100) : 0); ?>%"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts and Activity -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Task Progress Chart -->
    <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Task Progress</h2>
        <div class="flex items-center gap-2">
          <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Week</button>
          <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">Month</button>
          <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Year</button>
        </div>
      </div>
      
      <!-- Chart Placeholder -->
      <div class="h-64 bg-slate-50 dark:bg-slate-900/50 rounded-xl flex items-center justify-center">
        <div class="text-center">
          <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
          <p class="text-slate-500 dark:text-slate-400">Chart visualization would go here</p>
          <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Integrate with Chart.js or similar library</p>
        </div>
      </div>
      
      <!-- Chart Legend -->
      <div class="flex items-center justify-center gap-6 mt-6">
        <div class="flex items-center gap-2">
          <div class="w-3 h-3 rounded-full bg-blue-500"></div>
          <span class="text-sm text-slate-600 dark:text-slate-400">Completed</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-3 h-3 rounded-full bg-amber-500"></div>
          <span class="text-sm text-slate-600 dark:text-slate-400">Pending</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-3 h-3 rounded-full bg-slate-400"></div>
          <span class="text-sm text-slate-600 dark:text-slate-400">Overdue</span>
        </div>
      </div>
    </div>
    
    <!-- Activity Feed -->
    <div class="rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Activity</h2>
        <button class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">View All</button>
      </div>
      
      <div class="space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm text-slate-900 dark:text-white">Task "Design new landing page" completed</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">2 hours ago</p>
          </div>
        </div>
        
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm text-slate-900 dark:text-white">New task "Update documentation" created</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">5 hours ago</p>
          </div>
        </div>
        
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm text-slate-900 dark:text-white">Task "Fix navigation bug" due soon</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Yesterday</p>
          </div>
        </div>
        
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm text-slate-900 dark:text-white">Category "Development" created</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">2 days ago</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Tasks and Quick Actions -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Tasks -->
    <div class="rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Tasks</h2>
        <a href="<?php echo e(route('tasks.index')); ?>" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">View All</a>
      </div>
      
      <div class="space-y-3">
        <?php $__empty_1 = true; $__currentLoopData = $recentTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <div class="group flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all duration-200 relative">
    <div class="flex items-center gap-3">
      <div class="relative">
        <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 group-hover:border-blue-500 dark:group-hover:border-blue-400 transition-colors"></div>
        <?php if($task->completed): ?>
          <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
          </div>
        <?php endif; ?>
      </div>
      <div>
        <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors <?php echo e($task->completed ? 'line-through opacity-60' : ''); ?>">
          <?php echo e($task->title); ?>

        </p>
        <div class="flex items-center gap-2 mt-1">
          <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
            <?php echo e($task->category?->name ?? 'No category'); ?>

          </span>
          <span class="priority-indicator priority-<?php echo e($task->priority_color); ?>"></span>
        </div>
      </div>
    </div>

    <div class="text-right">
      <p class="text-sm text-slate-500 dark:text-slate-400"><?php echo e($task->formatted_due_date ?? 'No due date'); ?></p>
      <div class="flex items-center justify-end gap-1 mt-1 relative">
        <?php if($task->priority === 'high'): ?>
          <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
          </svg>
        <?php endif; ?>

        <!-- Tombol titik tiga -->
        <button 
          class="relative text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
          onclick="toggleMenu('<?php echo e($task->id); ?>')"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
            </path>
          </svg>
        </button>

        <!-- Dropdown Menu -->
        <!-- Dropdown Menu -->
<div id="menu-<?php echo e($task->id); ?>" 
  class="hidden absolute right-0 top-6 w-40 
         bg-white dark:bg-slate-800 
         border border-slate-200 dark:border-slate-700 
         rounded-xl shadow-lg z-[9999] 
         overflow-hidden text-sm animate-fade-in"
>
  <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" 
     class="flex items-center gap-2 px-4 py-2.5 
            text-slate-700 dark:text-slate-200 
            hover:bg-blue-50 dark:hover:bg-blue-900/30 
            transition-colors">
    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14" />
    </svg>
    Edit
  </a>

  <form action="<?php echo e(route('tasks.destroy', $task->id)); ?>" method="POST" 
        onsubmit="return confirm('Yakin ingin menghapus task ini?')">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="submit" 
      class="flex items-center gap-2 w-full text-left px-4 py-2.5 
             text-red-600 dark:text-red-400 
             hover:bg-red-50 dark:hover:bg-red-900/40 
             transition-colors">
      <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M6 18L18 6M6 6l12 12" />
      </svg>
      Hapus
    </button>
  </form>
</div>


      </div>
    </div>
  </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <div class="text-center py-8">
    <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
    </svg>
    <p class="text-slate-500 dark:text-slate-400">No recent tasks</p>
    <a href="<?php echo e(route('tasks.create')); ?>" class="inline-flex items-center mt-3 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">
      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
      </svg>
      Create your first task
    </a>
  </div>
<?php endif; ?>

      </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Quick Actions</h2>
      </div>
      
      <div class="grid grid-cols-2 gap-4">
        <a href="<?php echo e(route('tasks.create')); ?>" class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-5 text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
          <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          <div class="relative z-10 flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </div>
            <span class="font-medium">New Task</span>
          </div>
        </a>
        
        <a href="<?php echo e(route('categories.create')); ?>" class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
          <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          <div class="relative z-10 flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
            <span class="font-medium">New Category</span>
          </div>
        </a>
        
        <a href="<?php echo e(route('archive.tasks')); ?>" class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 p-5 text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
          <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          <div class="relative z-10 flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
              </svg>
            </div>
            <span class="font-medium">View Archive</span>
          </div>
        </a>
        
        <a href="<?php echo e(route('categories.index')); ?>" class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 p-5 text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
          <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          <div class="relative z-10 flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
              </svg>
            </div>
            <span class="font-medium">Categories</span>
          </div>
        </a>
      </div>
      
      <!-- Productivity Tip -->
      <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200/60 dark:border-blue-800/30">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-300">Productivity Tip</h3>
            <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">Break down large tasks into smaller, manageable chunks to stay motivated and track progress more effectively.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<script>
function toggleMenu(id) {
  document.querySelectorAll('[id^="menu-"]').forEach(el => {
    if (el.id !== `menu-${id}`) el.classList.add('hidden');
  });
  const menu = document.getElementById(`menu-${id}`);
  menu.classList.toggle('hidden');
}

// Tutup menu kalau klik di luar
document.addEventListener('click', function(e) {
  if (!e.target.closest('button[onclick^="toggleMenu"]') && !e.target.closest('[id^="menu-"]')) {
    document.querySelectorAll('[id^="menu-"]').forEach(el => el.classList.add('hidden'));
  }
});
</script>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\TODOLIST (2)\TODOLIST\TODOLIST\resources\views/dashboard/index.blade.php ENDPATH**/ ?>