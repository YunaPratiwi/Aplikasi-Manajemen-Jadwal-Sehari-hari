<?php ($title = 'Tambah Kategori'); ?>


<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-8">
  <!-- Header Section -->
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 p-8 shadow-xl">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white mb-2">Tambah Kategori Baru</h1>
        <p class="text-white/80 text-lg">Buat kategori baru untuk mengorganisir tugas Anda dengan lebih baik</p>
      </div>
      <div class="mt-4 md:mt-0 flex items-center gap-3">
        <a href="<?php echo e(route('categories.index')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-200 border border-white/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Kembali
        </a>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Section -->
    <div class="lg:col-span-2">
      <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-8 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full"></div>
        
        <form action="<?php echo e(route('categories.store')); ?>" method="POST" x-data="{ 
          name: '<?php echo e(old('name')); ?>', 
          description: '<?php echo e(old('description')); ?>', 
          color: '<?php echo e(old('color', '#22c55e')); ?>', 
          icon: '<?php echo e(old('icon', 'folder')); ?>',
          errors: {},
          validate() {
            this.errors = {};
            if (!this.name) this.errors.name = 'Nama kategori diperlukan';
            if (!this.color) this.errors.color = 'Warna diperlukan';
            return Object.keys(this.errors).length === 0;
          }
        }" @submit="if (!validate()) $el.preventDefault()">
          <?php echo csrf_field(); ?>
          
          <div class="space-y-6">
            <!-- Name Field -->
            <div>
              <label for="name" class="form-label">Nama Kategori</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                </div>
                <input 
                  type="text" 
                  id="name" 
                  name="name" 
                  x-model="name"
                  @blur="errors.name = name ? '' : 'Nama kategori diperlukan'"
                  class="form-input pl-10" 
                  placeholder="Masukkan nama kategori"
                  required
                  :class="{ 'border-red-300': errors.name }"
                >
              </div>
              <p x-show="errors.name" class="form-error" x-text="errors.name"></p>
              <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="form-error"><?php echo e($message); ?></p>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Description Field -->
            <div>
              <label for="description" class="form-label">Deskripsi</label>
              <div class="relative">
                <div class="absolute top-3 left-3 pointer-events-none">
                  <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <textarea 
                  id="description" 
                  name="description" 
                  x-model="description"
                  rows="4" 
                  class="form-input pl-10 resize-none" 
                  placeholder="Deskripsikan kategori ini (opsional)"
                ></textarea>
              </div>
              <p class="form-help">Deskripsi opsional untuk memberikan informasi tambahan tentang kategori ini</p>
            </div>

            <!-- Color and Icon Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Color Field -->
              <div>
                <label for="color" class="form-label">Warna Kategori</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <div class="w-5 h-5 rounded border-2 border-slate-300" :style="`background-color: ${color}`"></div>
                  </div>
                  <input 
                    type="text" 
                    id="color" 
                    name="color" 
                    x-model="color"
                    @blur="errors.color = color ? '' : 'Warna diperlukan'"
                    class="form-input pl-10 pr-10" 
                    placeholder="#22c55e"
                    required
                    :class="{ 'border-red-300': errors.color }"
                  >
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <input type="color" x-model="color" class="w-8 h-8 rounded cursor-pointer">
                  </div>
                </div>
                <p x-show="errors.color" class="form-error" x-text="errors.color"></p>
                <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="form-error"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                
                <!-- Preset Colors -->
                <div class="mt-3">
                  <p class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Warna Populer:</p>
                  <div class="flex flex-wrap gap-2">
                    <button type="button" @click="color = '#22c55e'" class="w-8 h-8 rounded-lg bg-emerald-500 hover:scale-110 transition-transform"></button>
                    <button type="button" @click="color = '#3b82f6'" class="w-8 h-8 rounded-lg bg-blue-500 hover:scale-110 transition-transform"></button>
                    <button type="button" @click="color = '#8b5cf6'" class="w-8 h-8 rounded-lg bg-violet-500 hover:scale-110 transition-transform"></button>
                    <button type="button" @click="color = '#ec4899'" class="w-8 h-8 rounded-lg bg-pink-500 hover:scale-110 transition-transform"></button>
                    <button type="button" @click="color = '#f59e0b'" class="w-8 h-8 rounded-lg bg-amber-500 hover:scale-110 transition-transform"></button>
                    <button type="button" @click="color = '#ef4444'" class="w-8 h-8 rounded-lg bg-red-500 hover:scale-110 transition-transform"></button>
                    <button type="button" @click="color = '#6b7280'" class="w-8 h-8 rounded-lg bg-slate-500 hover:scale-110 transition-transform"></button>
                    <button type="button" @click="color = '#06b6d4'" class="w-8 h-8 rounded-lg bg-cyan-500 hover:scale-110 transition-transform"></button>
                  </div>
                </div>
              </div>

              <!-- Icon Field -->
              <div>
                <label for="icon" class="form-label">Icon</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <input 
                    type="text" 
                    id="icon" 
                    name="icon" 
                    x-model="icon"
                    class="form-input pl-10" 
                    placeholder="folder"
                  >
                </div>
                <p class="form-help">Nama icon dari Heroicons (opsional)</p>
                
                <!-- Preset Icons -->
                <div class="mt-3">
                  <p class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Icon Populer:</p>
                  <div class="grid grid-cols-6 gap-2">
                    <button type="button" @click="icon = 'folder'" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                      <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                      </svg>
                    </button>
                    <button type="button" @click="icon = 'tag'" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                      <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                      </svg>
                    </button>
                    <button type="button" @click="icon = 'briefcase'" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                      <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      </svg>
                    </button>
                    <button type="button" @click="icon = 'calendar'" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                      <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                    </button>
                    <button type="button" @click="icon = 'home'" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                      <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                      </svg>
                    </button>
                    <button type="button" @click="icon = 'star'" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                      <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
              <a href="<?php echo e(route('categories.index')); ?>" class="btn-secondary">Batal</a>
              <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Kategori
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Preview Section -->
    <div class="lg:col-span-1">
      <div class="sticky top-6 space-y-6">
        <!-- Live Preview -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
          <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full"></div>
          <div class="relative z-10">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Preview</h3>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl shadow-md" :style="`background-color: ${color}`"></div>
                <div>
                  <h4 class="font-semibold text-slate-900 dark:text-white" x-text="name || 'Nama Kategori'"></h4>
                  <p class="text-sm text-slate-600 dark:text-slate-400" x-text="description || 'Deskripsi kategori'"></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tips Section -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 border border-blue-200/60 dark:border-blue-800/30">
          <div class="relative z-10">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-4">Tips & Panduan</h3>
            <div class="space-y-3">
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                </div>
                <p class="text-sm text-blue-800 dark:text-blue-200">Gunakan nama yang deskriptif dan mudah diingat</p>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                </div>
                <p class="text-sm text-blue-800 dark:text-blue-200">Pilih warna yang kontras untuk memudahkan identifikasi</p>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                </div>
                <p class="text-sm text-blue-800 dark:text-blue-200">Tambahkan deskripsi untuk memberikan konteks tambahan</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Categories -->
        <?php if(isset($recentCategories) && $recentCategories->count() > 0): ?>
          <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative z-10">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Kategori Terbaru</h3>
              <div class="space-y-3">
                <?php $__currentLoopData = $recentCategories->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="w-8 h-8 rounded-lg" style="background-color: <?php echo e($recentCategory->color); ?>"></div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-slate-900 dark:text-white truncate"><?php echo e($recentCategory->name); ?></p>
                      <p class="text-xs text-slate-500 dark:text-slate-400"><?php echo e($recentCategory->tasks_count ?? 0); ?> tugas</p>
                    </div>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\TODOLIST\TODOLIST\resources\views/categories/create.blade.php ENDPATH**/ ?>