<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#f8fafc" media="(prefers-color-scheme: light)">
    <title>Daftar - TodoList</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-success-500/5 via-transparent to-primary-500/5 dark:from-success-400/10 dark:to-primary-400/10">
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- Brand Panel -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-success-600/20 via-success-500/10 to-primary-500/15 dark:from-success-900/30 dark:via-success-800/20 dark:to-primary-900/30">
            <div class="absolute inset-0 bg-gradient-to-br from-success-600/30 via-success-500/10 to-primary-500/20"></div>
            <div class="absolute -top-48 -left-48 w-[600px] h-[600px] rounded-full bg-success-400/15 blur-3xl"></div>
            <div class="absolute -bottom-48 -right-48 w-[600px] h-[600px] rounded-full bg-primary-400/15 blur-3xl"></div>

            <div class="relative z-10 w-full flex flex-col justify-center px-16 py-12">
                <div class="max-w-lg">
                    <div class="flex items-center gap-4 mb-12">
                        <div class="w-16 h-16 bg-white/20 ring-1 ring-white/30 rounded-xl flex items-center justify-center shadow-lg backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white drop-shadow-sm">Buat akun TodoList</h1>
                    </div>

                    <h2 class="text-4xl font-bold text-white leading-tight mb-6">Mulai lebih teratur dengan daftar tugas yang cerdas</h2>

                    <p class="text-white/90 text-lg mb-10">
                        Sinkronkan pekerjaan, tetapkan prioritas, dan capai target harian Anda dengan UI yang bersih dan aksesibel.
                    </p>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="mt-1 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-medium mb-1">Validasi real-time</h3>
                                <p class="text-white/80 text-sm">Formulir aman dengan validasi langsung dan interaktif.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="mt-1 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-medium mb-1">Komponen reusable</h3>
                                <p class="text-white/80 text-sm">Antarmuka konsisten dan mudah dinavigasi.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="mt-1 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-medium mb-1">Responsif di semua perangkat</h3>
                                <p class="text-white/80 text-sm">Pengalaman optimal di desktop, tablet, dan mobile.</p>
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
                    <div class="mx-auto w-16 h-16 bg-success-600 rounded-xl flex items-center justify-center shadow-md mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Buat akun baru</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                            Masuk di sini
                        </a>
                    </p>
                </div>

                <div class="card p-8 shadow-xl border border-gray-100 dark:border-gray-700 rounded-2xl">
                    @if($errors->any())
                        <div class="mb-6 notification-error" role="alert" aria-live="assertive">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form x-data="registerForm()" @submit="handleSubmit" class="space-y-6" action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input id="name" name="name" type="text" x-model="name"
                                       class="form-input h-12 text-base pl-14"
                                       placeholder="Nama lengkap Anda" required>
                            </div>
                            <p x-show="errors.name" class="form-error" x-text="errors.name"></p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 12H8m0 0l4-4m-4 4l4 4"/>
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" x-model="email"
                                       class="form-input h-12 text-base pl-14"
                                       placeholder="nama@example.com" required>
                            </div>
                            <p x-show="errors.email" class="form-error" x-text="errors.email"></p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 11c.727 0 1.333.607 1.333 1.333V13h-2.666v-.667C10.667 11.607 11.273 11 12 11z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 9V7a6 6 0 1112 0v2"/>
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" x-model="password"
                                       class="form-input h-12 text-base pl-14"
                                       placeholder="••••••••" required>
                            </div>
                            <p x-show="errors.password" class="form-error" x-text="errors.password"></p>
                        </div>

                        <!-- Konfirmasi -->
                        <div>
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password" x-model="confirm"
                                       class="form-input h-12 text-base pl-14"
                                       placeholder="Ulangi password" required>
                            </div>
                            <p x-show="errors.confirm" class="form-error" x-text="errors.confirm"></p>
                        </div>

                        <!-- Tombol daftar -->
                        <div>
                            <button type="submit" class="btn-success w-full h-12">
                                <span x-show="!loading">Daftar Sekarang</span>
                                <span x-show="loading" class="inline-flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>

                        <!-- Tombol kembali ke login -->
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition">
                                ← Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>

                <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    © {{ date('Y') }} TodoList. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>

    <script>
        function registerForm() {
            return {
                name: '', email: '', password: '', confirm: '', loading: false,
                errors: { name: '', email: '', password: '', confirm: '' },
                handleSubmit(event) {
                    this.errors = {};
                    if (this.name.length < 2) this.errors.name = 'Nama minimal 2 karakter.';
                    if (!/\S+@\S+\.\S+/.test(this.email)) this.errors.email = 'Email tidak valid.';
                    if (this.password.length < 8) this.errors.password = 'Password minimal 8 karakter.';
                    if (this.confirm !== this.password) this.errors.confirm = 'Konfirmasi tidak sama.';
                    if (Object.keys(this.errors).length) { event.preventDefault(); return; }
                    this.loading = true;
                }
            };
        }
    </script>
</body>
</html>
