<?php ($title = 'Categories'); ?>


<?php $__env->startSection('content'); ?>
<div class="space-y-8">
  <!-- Header Section -->
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 p-8 shadow-xl">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white mb-2">Kategori</h1>
        <p class="text-white/80 text-lg">Kelola dan organisir kategori tugas Anda dengan lebih efisien</p>
      </div>
      <div class="mt-4 md:mt-0 flex items-center gap-3">
        <a href="<?php echo e(route('archive.categories')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-200 border border-white/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
          </svg>
          Arsipkan
        </a>
        <a href="<?php echo e(route('categories.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-600 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Tambah Kategori
        </a>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Kategori</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($totalCategories ?? $categories->total()); ?></p>
      </div>
    </div>
    
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Aktif</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($activeCategories ?? $categories->where('is_active', true)->count()); ?></p>
      </div>
    </div>
    
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/10 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Non-aktif</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($inactiveCategories ?? $categories->where('is_active', false)->count()); ?></p>
      </div>
    </div>
    
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Tugas</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($totalTasks ?? 0); ?></p>
      </div>
    </div>
  </div>

  <!-- Search and Filter Section -->
  <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
    <form method="GET" action="<?php echo e(route('categories.index')); ?>" class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
          <label class="form-label">Cari Kategori</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <input 
              type="text" 
              name="search" 
              value="<?php echo e(request('search')); ?>" 
              class="form-input pl-10 pr-4 py-2.5 w-full rounded-xl border-slate-200 dark:border-slate-700 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400/20" 
              placeholder="Cari berdasarkan nama atau deskripsi..."
            >
          </div>
        </div>
        
        <div class="flex flex-col justify-end space-y-3">
          <div class="flex items-center">
            <input type="checkbox" name="show_inactive" value="1" <?php if(request('show_inactive')): echo 'checked'; endif; ?> id="show_inactive" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
            <label for="show_inactive" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">
              Tampilkan kategori non-aktif
            </label>
          </div>
          
          <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Terapkan Filter
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Categories Grid -->
  <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
      <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/5 to-transparent rounded-bl-full"></div>
      <div class="relative z-10">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="relative">
              <div class="w-12 h-12 rounded-xl shadow-md" style="background-color: <?php echo e($category->color); ?>"></div>
              <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center border-2 border-white dark:border-slate-800">
                <svg class="w-3 h-3 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
              </div>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                <a href="<?php echo e(route('categories.show', $category)); ?>"><?php echo e($category->name); ?></a>
              </h3>
              <div class="flex items-center gap-2 mt-1">
                <?php if (! ($category->is_active)): ?>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">Non-aktif</span>
                <?php endif; ?>
                <span class="text-xs text-slate-500 dark:text-slate-400"><?php echo e($category->tasks_count ?? 0); ?> tugas</span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-1">
            <form action="<?php echo e(route('categories.toggle-active', $category)); ?>" method="POST">
              <?php echo csrf_field(); ?>
              <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200" title="<?php echo e($category->is_active ? 'Non-aktifkan' : 'Aktifkan'); ?>">
                <?php if($category->is_active): ?>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                  </svg>
                <?php else: ?>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                <?php endif; ?>
              </button>
            </form>
            <a href="<?php echo e(route('categories.edit', $category)); ?>" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200" title="Edit">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </a>
            <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
              <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
              <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200" title="Hapus">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </form>
          </div>
        </div>
        
        <p class="text-slate-600 dark:text-slate-400 mb-4"><?php echo e($category->description ?: 'Tidak ada deskripsi'); ?></p>
        
        <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-700">
          <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <span><?php echo e($category->formatted_created_at ?? 'Tidak diketahui'); ?></span>
            </div>
            <?php if($category->tasks_count > 0): ?>
              <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span><?php echo e($category->tasks_count); ?> tugas</span>
              </div>
            <?php endif; ?>
          </div>
          <div class="flex items-center gap-2">
            <?php if($category->completed_tasks_count > 0): ?>
              <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                <?php echo e($category->completed_tasks_count); ?> selesai
              </span>
            <?php endif; ?>
            <a href="<?php echo e(route('categories.show', $category)); ?>" class="text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 text-sm font-medium transition-colors">
              Lihat Detail →
            </a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-12 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
      <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20"></div>
      <div class="relative z-10 text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center">
          <svg class="w-10 h-10 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">Tidak ada kategori ditemukan</h3>
        <p class="text-slate-600 dark:text-slate-400 mb-6 max-w-md mx-auto">
          <?php if(request('search')): ?>
            Tidak ada kategori yang cocok dengan pencarian "<?php echo e(request('search')); ?>".
          <?php else: ?>
            Mulai dengan membuat kategori pertama Anda untuk mengorganisir tugas dengan lebih baik.
          <?php endif; ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <?php if(request('search')): ?>
            <a href="<?php echo e(route('categories.index')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
              </svg>
              Hapus Filter
            </a>
          <?php endif; ?>
          <a href="<?php echo e(route('categories.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white font-medium rounded-xl hover:bg-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat Kategori Baru
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Enhanced Pagination -->
  <?php if($categories->hasPages()): ?>
    <div class="flex items-center justify-between">
      <div class="text-sm text-slate-700 dark:text-slate-300">
        Menampilkan <span class="font-medium"><?php echo e($categories->firstItem()); ?></span> hingga <span class="font-medium"><?php echo e($categories->lastItem()); ?></span> dari <span class="font-medium"><?php echo e($categories->total()); ?></span> hasil
      </div>
      <div class="flex items-center gap-2">
        
        <?php if($categories->onFirstPage()): ?>
          <button disabled class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg cursor-not-allowed">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Previous
          </button>
        <?php else: ?>
          <a href="<?php echo e($categories->previousPageUrl()); ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Previous
          </a>
        <?php endif; ?>

        
        <div class="hidden md:flex items-center gap-1">
          <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_string($element)): ?>
              <span class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300"><?php echo e($element); ?></span>
            <?php else: ?>
              <?php if($element->isCurrent()): ?>
                <span class="relative z-10 inline-flex items-center px-4 py-2 text-sm font-medium bg-purple-600 text-white rounded-lg"><?php echo e($element->page); ?></span>
              <?php else: ?>
                <a href="<?php echo e($element->url()); ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"><?php echo e($element->page); ?></a>
              <?php endif; ?>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($categories->hasMorePages()): ?>
          <a href="<?php echo e($categories->nextPageUrl()); ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            Next
            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        <?php else: ?>
          <button disabled class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg cursor-not-allowed">
            Next
            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\TODOLIST\TODOLIST\resources\views/categories/index.blade.php ENDPATH**/ ?>