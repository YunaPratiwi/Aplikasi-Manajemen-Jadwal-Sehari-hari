@props([
    'title',
    'message',
    'icon' => null,
    'actionUrl' => null,
    'actionText' => null
])

<div class="text-center py-12">
    @if($icon)
        {!! $icon !!}
    @else
        <svg class="w-16 h-16 mx-auto mb-4 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
    @endif
    
    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">{{ $title }}</h3>
    <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto">{{ $message }}</p>
    
    @if($slot->isNotEmpty())
        {{ $slot }}
    @elseif($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="btn-primary inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ $actionText }}
        </a>
    @endif
</div>
