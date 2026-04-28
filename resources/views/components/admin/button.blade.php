@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-md hover:shadow-blue-600/25',
        'secondary' => 'bg-orange-500 text-white hover:bg-orange-600 hover:shadow-md hover:shadow-orange-500/25',
        'outline' => 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50',
        'ghost-blue' => 'text-blue-600 hover:bg-blue-50',
        'ghost-danger' => 'text-red-600 hover:bg-red-50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs gap-1.5',
        'md' => 'px-4 py-2.5 text-sm gap-2',
        'icon' => 'h-8 w-8 justify-center',
    ];

    $classes = trim('inline-flex items-center rounded-lg font-semibold transition disabled:cursor-not-allowed disabled:opacity-60 '
        . ($variants[$variant] ?? $variants['primary']) . ' '
        . ($sizes[$size] ?? $sizes['md']));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)<i class="fas {{ $icon }}"></i>@endif
        @if (trim($slot) !== '')<span>{{ $slot }}</span>@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon)<i class="fas {{ $icon }}"></i>@endif
        @if (trim($slot) !== '')<span>{{ $slot }}</span>@endif
    </button>
@endif
