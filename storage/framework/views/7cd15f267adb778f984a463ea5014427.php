<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#f8fafc" media="(prefers-color-scheme: light)">
    <meta name="description" content="Masuk ke akun TodoList Anda untuk mengelola tugas dengan efisien">
    <meta name="keywords" content="login, masuk, todo, tugas, produktivitas">
    <meta name="author" content="TodoList">
    <meta property="og:title" content="Login - TodoList">
    <meta property="og:description" content="Masuk ke akun TodoList Anda untuk mengelola tugas dengan efisien">
    <meta property="og:type" content="website">
    <title>Login - TodoList</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-500/5 via-transparent to-success-500/5 dark:from-primary-400/10 dark:to-success-400/10">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                class="fixed top-4 right-4 z-50 p-2 rounded-full bg-white/20 dark:bg-gray-800/20 backdrop-blur-sm border border-white/30 dark:border-gray-700/30 text-gray-700 dark:text-gray-300 hover:bg-white/30 dark:hover:bg-gray-800/30 transition-colors"
                aria-label="Toggle dark mode">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>

        <!-- Brand Panel -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-primary-600/20 via-primary-500/10 to-success-500/15 dark:from-primary-900/30 dark:via-primary-800/20 dark:to-success-900/30">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600/30 via-primary-500/10 to-success-500/20"></div>
            <div class="absolute -top-48 -left-48 w-[600px] h-[600px] rounded-full bg-primary-400/15 blur-3xl"></div>
            <div class="absolute -bottom-48 -right-48 w-[600px] h-[600px] rounded-full bg-success-400/15 blur-3xl"></div>
            <div class="relative z-10 w-full flex flex-col justify-center px-16 py-12">
                <div class="max-w-lg">
                    <div class="flex items-center gap-4 mb-12">
                        <div class="w-16 h-16 bg-white/20 ring-1 ring-white/30 rounded-xl flex items-center justify-center shadow-lg backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white drop-shadow-sm">TodoList</h1>
                    </div>
                    
                    <h2 class="text-4xl font-bold text-white leading-tight mb-6">Kelola tugas Anda dengan tenang dan terorganisir</h2>
                    
                    <p class="text-white/90 text-lg mb-10">Fokus pada yang penting. Buat, atur, dan rampungkan tugas dengan fitur drag-and-drop, kategori, dan pencarian cepat.</p>
                    
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-medium mb-1">Tampilan modern dan responsif</h3>
                                <p class="text-white/80 text-sm">Antarmuka yang bersih dan intuitif untuk pengalaman pengguna yang optimal</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-medium mb-1">Komponen konsisten</h3>
                                <p class="text-white/80 text-sm">Sistem desain yang terintegrasi untuk pengalaman yang kohesif</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-medium mb-1">Animasi halus</h3>
                                <p class="text-white/80 text-sm">Transisi yang lembut untuk interaksi yang menyenangkan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Panel -->
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-12 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm">
            <div class="w-full max-w-md">
                <div class="text-center mb-10 lg:hidden">
                    <div class="mx-auto w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center shadow-md mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Masuk ke akun Anda</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Atau <a href="<?php echo e(route('register')); ?>" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">daftar akun baru</a></p>
                </div>

                <div class="card p-8 shadow-xl">
                    <!-- Success Message -->
                    <?php if(session('success')): ?>
                        <div class="mb-6 notification-success animate-fade-in" role="status" aria-live="polite">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <?php echo e(session('success')); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Error Messages -->
                    <?php if($errors->any()): ?>
                        <div class="mb-6 notification-error animate-fade-in" role="alert" aria-live="assertive">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-medium">Terjadi kesalahan:</p>
                                    <ul class="list-disc list-inside text-sm mt-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form 
    x-data="{ 
        email: '<?php echo e(old('email')); ?>', 
        password: '', 
        showPassword: false,
        errors: { email: '', password: '' }, 
        loading: false,
        submitted: false,
        demoEmail: '<?php echo e(env('DEMO_USER_EMAIL')); ?>',
        demoPassword: '<?php echo e(env('DEMO_USER_PASSWORD')); ?>',
        validate() {
            this.errors.email = this.email ? '' : 'Email diperlukan';
            this.errors.password = this.password ? '' : 'Password diperlukan';
            
            if (this.email && !/\S+@\S+\.\S+/.test(this.email)) {
                this.errors.email = 'Format email tidak valid';
            }
            
            if (this.password && this.password.length < 8) {
                this.errors.password = 'Password minimal 8 karakter';
            }
            
            return !this.errors.email && !this.errors.password;
        }
    }"
    @submit="if (!validate()) { $el.preventDefault(); } else { loading = true; submitted = true; }"
    class="space-y-6"
    action="<?php echo e(route('login')); ?>"
    method="POST">
    <?php echo csrf_field(); ?>

    <!-- EMAIL -->
    <div>
        <label for="email" class="form-label">Email</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
            </div>
            <input id="email" name="email" type="email" autocomplete="email" required
                x-model="email"
                @blur="errors.email = email ? '' : 'Email diperlukan'"
                @input="errors.email = /\S+@\S+\.\S+/.test(email) ? '' : 'Format email tidak valid'"
                class="form-input h-12 text-base pl-14 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                style="padding-left: 3.5rem !important;"
                :class="{ 'border-red-500': errors.email }"
                placeholder="nama@example.com">
        </div>
        <p x-show="errors.email" class="form-error" x-text="errors.email"></p>
        <?php $__errorArgs = ['email'];
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

    <!-- PASSWORD -->
    <div>
        <label for="password" class="form-label">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" required
                x-model="password"
                @blur="errors.password = password ? '' : 'Password diperlukan'"
                @input="errors.password = password.length >= 8 ? '' : 'Password minimal 8 karakter'"
                class="form-input h-12 text-base pl-14 pr-12 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                style="padding-left: 3.5rem !important;"
                :class="{ 'border-red-500': errors.password }"
                placeholder="••••••••">
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <button type="button" @click="showPassword = !showPassword" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
        </div>
        <p x-show="errors.password" class="form-error" x-text="errors.password"></p>
        <?php $__errorArgs = ['password'];
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

    <!-- REMEMBER + FORGOT -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-200">
                Ingat saya
            </label>
        </div>

        <?php if(Route::has('password.request')): ?>
        <div class="text-sm">
            <a href="<?php echo e(route('password.request')); ?>"
                class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                Lupa password?
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- BUTTON -->
    <div>
        <button type="submit" :disabled="loading"
            class="btn-primary w-full h-12 flex justify-center items-center relative overflow-hidden group">
            <span x-show="!loading" class="inline-flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                        clip-rule="evenodd" />
                </svg>
                Masuk
            </span>
            <span x-show="loading" class="inline-flex items-center">
                <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Memproses...
            </span>
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
        </button>
    </div>
</form>


                    <?php echo $__env->make('auth.partials.social-login', ['label' => 'Atau lanjutkan dengan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <!-- Register Now CTA -->
                    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-300">
                        Belum punya akun?
                        <a href="<?php echo e(route('register')); ?>" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Daftar sekarang</a>
                    </div>
                </div>
                
                <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    <p>© <?php echo e(date('Y')); ?> TodoList. Semua hak dilindungi.</p>
                    <p class="mt-2">
                        <a href="#" class="hover:text-gray-900 dark:hover:text-white transition-colors">Kebijakan Privasi</a>
                        <span class="mx-1">·</span>
                        <a href="#" class="hover:text-gray-900 dark:hover:text-white transition-colors">Syarat & Ketentuan</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts Help Modal -->
    <div x-data="{ open: false }" @keydown.escape.window="open = false">
        <button @click="open = true" class="fixed bottom-4 right-4 p-2 rounded-full bg-primary-600 text-white shadow-lg hover:bg-primary-700 transition-colors" aria-label="Bantuan">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </button>
        
        <div x-show="open" x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     @click="open = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Bantuan Login
                                </h3>
                                <div class="mt-4">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <p class="mb-4">Jika Anda mengalami masalah saat login, silakan coba hal berikut:</p>
                                        <ul class="list-disc list-inside space-y-2">
                                            <li>Periksa kembali email dan password yang Anda masukkan</li>
                                            <li>Pastikan caps lock tidak aktif</li>
                                            <li>Coba gunakan browser lain atau clear cache browser Anda</li>
                                            <li>Jika lupa password, gunakan fitur "Lupa password"</li>
                                            <li>Hubungi tim dukungan jika masalah berlanjut</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\TODOLIST (2)\TODOLIST\TODOLIST\resources\views/auth/login.blade.php ENDPATH**/ ?>