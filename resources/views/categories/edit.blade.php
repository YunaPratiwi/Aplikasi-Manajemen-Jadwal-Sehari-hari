@php($title = 'Edit Kategori')
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
  <!-- Header Section -->
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 shadow-xl">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white mb-2">Edit Kategori</h1>
        <p class="text-white/80 text-lg">Perbarui informasi kategori "{{ $category->name }}"</p>
      </div>
      <div class="mt-4 md:mt-0 flex items-center gap-3">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-200 border border-white/20">
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
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
        
        <form action="{{ route('categories.update', $category) }}" method="POST" x-data="{ 
          name: '{{ old('name', $category->name) }}', 
          description: '{{ old('description', $category->description) }}', 
          color: '{{ old('color', $category->color) }}', 
          icon: '{{ old('icon', $category->icon) }}',
          isActive: {{ old('is_active', $category->is_active) ? 'true' : 'false' }},
          originalName: '{{ $category->name }}',
          originalColor: '{{ $category->color }}',
          originalIcon: '{{ $category->icon }}',
          hasChanges: false,
          errors: {},
          validate() {
            this.errors = {};
            if (!this.name) this.errors.name = 'Nama kategori diperlukan';
            if (!this.color) this.errors.color = 'Warna diperlukan';
            return Object.keys(this.errors).length === 0;
          },
          checkChanges() {
            this.hasChanges = this.name !== this.originalName || 
                             this.color !== this.originalColor || 
                             this.icon !== this.originalIcon || 
                             this.description !== '{{ $category->description }}' ||
                             this.isActive !== {{ $category->is_active ? 'true' : 'false' }};
          }
        }" 
        x-init="$watch('name', () => checkChanges()); $watch('color', () => checkChanges()); $watch('icon', () => checkChanges()); $watch('description', () => checkChanges()); $watch('isActive', () => checkChanges());"
        @submit="if (!validate()) $el.preventDefault()">
          @csrf @method('PUT')
          
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
              @error('name')
                <p class="form-error">{{ $message }}</p>
              @enderror
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
                @error('color')
                  <p class="form-error">{{ $message }}</p>
                @enderror
                
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

            <!-- Status Field -->
            <div>
              <div class="flex items-center justify-between">
                <label class="form-label">Status Kategori</label>
                <div class="flex items-center gap-2">
                  <div class="w-2 h-2 rounded-full" :class="isActive ? 'bg-emerald-500' : 'bg-slate-400'"></div>
                  <span class="text-sm text-slate-600 dark:text-slate-400" x-text="isActive ? 'Aktif' : 'Non-aktif'"></span>
                </div>
              </div>
              <div class="mt-3">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" name="is_active" value="1" x-model="isActive" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                    <span x-show="!isActive">Non-aktif</span>
                    <span x-show="isActive">Aktif</span>
                  </span>
                </label>
                <p class="form-help mt-2">Kategori non-aktif tidak akan muncul dalam daftar kategori</p>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-slate-200 dark:border-slate-700">
              <div class="flex items-center gap-2">
                <button type="button" @click="name = originalName; color = originalColor; icon = originalIcon; description = '{{ $category->description }}'; isActive = {{ $category->is_active ? 'true' : 'false' }};" 
                        x-show="hasChanges"
                        class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                  Reset Changes
                </button>
              </div>
              <div class="flex gap-3">
                <a href="{{ route('categories.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  Simpan Perubahan
                </button>
              </div>
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
          <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
          <div class="relative z-10">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Preview</h3>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl shadow-md" :style="`background-color: ${color}`"></div>
                <div>
                  <h4 class="font-semibold text-slate-900 dark:text-white" x-text="name || 'Nama Kategori'"></h4>
                  <p class="text-sm text-slate-600 dark:text-slate-400" x-text="description || 'Deskripsi kategori'"></p>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" 
                          :class="isActive ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300'" 
                          x-text="isActive ? 'Aktif' : 'Non-aktif'"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Category Stats -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60">
          <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-500/10 to-transparent rounded-bl-full"></div>
          <div class="relative z-10">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Statistik Kategori</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-sm text-slate-600 dark:text-slate-400">Total Tugas</span>
                <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $category->tasks_count ?? 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-slate-600 dark:text-slate-400">Tugas Selesai</span>
                <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $category->completed_tasks_count ?? 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-slate-600 dark:text-slate-400">Dibuat Pada</span>
                <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $category->formatted_created_at ?? 'Tidak diketahui' }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-slate-600 dark:text-slate-400">Terakhir Diubah</span>
                <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $category->formatted_updated_at ?? 'Tidak diketahui' }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-red-200/60 dark:border-red-800/30">
          <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-transparent rounded-bl-full"></div>
          <div class="relative z-10">
            <h3 class="text-lg font-semibold text-red-900 dark:text-red-300 mb-4">Danger Zone</h3>
            <div class="space-y-3">
              <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30">
                <h4 class="text-sm font-medium text-red-900 dark:text-red-300 mb-1">Arsipkan Kategori</h4>
                <p class="text-xs text-red-700 dark:text-red-400 mb-3">Arsipkan kategori ini jika tidak lagi digunakan</p>
                <form action="{{ route('categories.toggleActive', $category) }}" method="POST">
                  @csrf
                  <button type="submit" class="text-sm px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                    Arsipkan
                  </button>
                </form>
              </div>
              
              <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30">
                <h4 class="text-sm font-medium text-red-900 dark:text-red-300 mb-1">Hapus Kategori</h4>
                <p class="text-xs text-red-700 dark:text-red-400 mb-3">Hapus kategori ini secara permanen</p>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="text-sm px-3 py-1.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Hapus
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection