@extends('layouts.admin')

@section('title', 'Daftar Mentee')

@section('content')
<div x-data="{
    selectedMentee: null,
    milestones: [],
    selectMentee(mentee) {
        this.selectedMentee = mentee;
        this.milestones = mentee.milestones || [];
    }
}">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-800">Daftar Mahasiswa Bimbingan (Mentee)</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola dan pantau perkembangan target/milestone mahasiswa di bawah bimbingan Anda.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        {{-- Left Side: Mentees List (2/3 width) --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <x-admin.table :columns="['Nama', 'Email', 'Institusi', 'Progress Milestone', 'Aksi']">
                    @forelse($mentees as $mentee)
                        @php
                            $total = $mentee->milestones->count();
                            $completed = $mentee->milestones->where('completed', true)->count();
                            $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/60 cursor-pointer" x-on:click="selectMentee({{ json_encode($mentee) }})">
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $mentee->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm">
                                {{ $mentee->email }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm">
                                {{ $mentee->institution ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-slate-100 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600">{{ $completed }}/{{ $total }} ({{ $percent }}%)</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 hover:text-purple-700">
                                    Detail Tracker <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-graduation-cap text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada mahasiswa bimbingan terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </x-admin.table>
            </div>
        </div>

        {{-- Right Side: Milestone Tracker & Feedback Column (1/3 width) --}}
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm min-h-[300px]">
                <template x-if="!selectedMentee">
                    <div class="flex flex-col items-center justify-center text-center py-20 text-slate-400">
                        <i class="fa-solid fa-arrow-left text-3xl mb-3 text-purple-300"></i>
                        <p class="text-sm font-semibold text-slate-500">Pilih Mahasiswa</p>
                        <p class="text-xs">Klik salah satu baris mahasiswa untuk melihat Milestone Tracker & memberikan feedback bimbingan.</p>
                    </div>
                </template>

                <template x-if="selectedMentee">
                    <div>
                        <div class="border-b border-slate-100 pb-4 mb-4">
                            <h3 class="font-extrabold text-navy-600 text-lg" x-text="selectedMentee.first_name + ' ' + selectedMentee.last_name"></h3>
                            <p class="text-xs text-slate-450" x-text="selectedMentee.institution || 'Tidak ada institusi'"></p>
                        </div>

                        <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider mb-3">Milestone Progress</h4>
                        
                        <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                            <template x-if="milestones.length === 0">
                                <p class="text-xs text-slate-400 italic">Mahasiswa belum menambahkan milestone.</p>
                            </template>

                            <template x-for="(milestone, idx) in milestones" :key="milestone.id">
                                <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 space-y-2">
                                    <div class="flex items-start gap-2.5 justify-between">
                                        <div class="flex gap-2 items-start">
                                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border text-[10px] mt-0.5"
                                                :class="milestone.completed ? 'bg-purple-500 border-purple-500 text-white' : 'border-slate-300 bg-white'">
                                                <i class="fa-solid fa-check" x-show="milestone.completed"></i>
                                            </span>
                                            <span class="text-sm font-semibold text-slate-700" :class="milestone.completed ? 'line-through text-slate-400' : ''" x-text="milestone.text"></span>
                                        </div>
                                    </div>

                                    {{-- Feedback Form --}}
                                    <form :action="'/mentor/milestones/' + milestone.id + '/feedback'" method="POST" class="mt-3">
                                        @csrf
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Feedback Mentor</label>
                                        <div class="flex gap-1.5">
                                            <input type="text" name="feedback" :value="milestone.feedback" placeholder="Ketik feedback di sini..."
                                                class="w-full rounded-lg border border-slate-200 px-3 py-1.5 text-xs focus:border-purple-500 focus:outline-none">
                                            <button type="submit" class="rounded-lg bg-purple-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-purple-700 shrink-0">
                                                Kirim
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
