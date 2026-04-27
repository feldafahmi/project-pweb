@props([
    'icon' => 'fa-circle-info',
    'title',
    'description' => null,
])

<div class="flex flex-col items-center justify-center px-6 py-16 text-center">
    <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-400">
        <i class="fas {{ $icon }} text-3xl"></i>
    </div>

    <h3 class="mb-2 text-lg font-bold text-navy-600">{{ $title }}</h3>

    @if ($description)
        <p class="mb-6 max-w-md text-sm leading-relaxed text-slate-500">{{ $description }}</p>
    @endif

    @isset($cta)
        <div class="mt-1">{{ $cta }}</div>
    @endisset
</div>
