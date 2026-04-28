@props([
    'route',
    'label',
    'icon',
    'aliases' => [],
])

@php
    $active = request()->routeIs($route) || (count($aliases) && request()->routeIs(...$aliases));
@endphp

<a href="{{ route($route) }}"
    @class([
        'group relative flex items-center gap-3 rounded-r-lg py-3 pl-5 pr-4 text-sm font-semibold transition-colors',
        'border-l-4 border-blue-600 bg-blue-50 text-blue-600' => $active,
        'border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-blue-600' => ! $active,
    ])>
    <i class="fas {{ $icon }} w-5 text-center {{ $active ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
    <span>{{ $label }}</span>
</a>
