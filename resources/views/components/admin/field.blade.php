@props([
    'label',
    'name' => null,
    'required' => false,
    'hint' => null,
])

<div {{ $attributes->only('class') }}>
    <label @if ($name) for="{{ $name }}" @endif
        class="mb-1.5 block text-xs font-semibold text-slate-600">
        {{ $label }}
        @if ($required)<span class="ml-0.5 text-red-500">*</span>@endif
    </label>

    {{ $slot }}

    @if ($hint)
        <p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>
    @endif
</div>
