@props(['tone' => 'slate'])

@php
    $tones = [
        'slate' => 'bg-slate-100 text-slate-700',
        'blue' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-100',
        'orange' => 'bg-orange-50 text-orange-700 ring-1 ring-orange-100',
        'green' => 'bg-green-50 text-green-700 ring-1 ring-green-100',
        'red' => 'bg-red-50 text-red-700 ring-1 ring-red-100',
        'purple' => 'bg-purple-50 text-purple-700 ring-1 ring-purple-100',
        'yellow' => 'bg-yellow-50 text-yellow-800 ring-1 ring-yellow-100',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold',
    $tones[$tone] ?? $tones['slate'],
]) }}>
    {{ $slot }}
</span>
