@props(['current'])

<div class="border-b border-slate-200 bg-slate-50 py-4 text-sm">
    <div class="mu-container flex items-center gap-2 text-slate-500">
        <a href="{{ url('/') }}" class="text-navy-600 transition-colors hover:text-navy-800" aria-label="Home">
            <i class="fa-solid fa-house"></i>
        </a>
        <span>/</span>
        <span class="font-semibold text-navy-600">{{ $current }}</span>
    </div>
</div>
