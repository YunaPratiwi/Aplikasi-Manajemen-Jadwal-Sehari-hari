@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'trend' => null,
    'progress' => null
])

<div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-lg border border-slate-200/60 dark:border-slate-700/60 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-{{ $color }}-500/10 to-transparent rounded-bl-full"></div>
    <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 flex items-center justify-center shadow-lg">
                {!! $icon !!}
            </div>
            @if($trend)
                <div class="flex items-center gap-1 text-{{ $color }}-600 dark:text-{{ $color }}-400 text-sm font-medium">
                    @if(str_starts_with($trend, '+'))
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    @endif
                    <span>{{ $trend }}</span>
                </div>
            @endif
        </div>
        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">{{ $title }}</h3>
        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $value }}</p>
        
        @if($progress !== null)
            <div class="mt-4 w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
        @endif
        
        {{ $slot }}
    </div>
</div>
