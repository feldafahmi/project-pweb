@props([
    'name',
    'title',
    'size' => 'lg',
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
@endphp

<div x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail?.name === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail?.name === '{{ $name }}' || !$event.detail) open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[60] overflow-y-auto"
    role="dialog"
    aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div class="flex min-h-full items-start justify-center p-4 sm:items-center sm:p-6">
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="relative w-full {{ $sizes[$size] ?? $sizes['lg'] }} rounded-2xl bg-white shadow-2xl">

            <header class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-extrabold text-slate-800">{{ $title }}</h2>
                <button type="button" @click="open = false"
                    class="-mr-2 -mt-1 inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                    aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </header>

            <div class="px-6 py-5">
                {{ $slot }}
            </div>

            @isset($footer)
                <footer class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4">
                    {{ $footer }}
                </footer>
            @endisset
        </div>
    </div>
</div>
