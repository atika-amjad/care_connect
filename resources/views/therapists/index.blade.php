@extends('layouts.app')

@section('title', __('app.nav.therapists'))
@php $pageKey = 'therapists'; @endphp

@section('content')
<div x-data="{ modalOpen: false, therapistId: null, therapistName: '' }">
    <div class="mb-5 flex flex-wrap gap-2">
        @foreach($filters as $f)
            <a href="{{ route('therapists.index', ['filter' => $f]) }}"
               class="rounded-full border px-4 py-1.5 text-xs font-bold transition {{ $filter === $f ? 'border-brand-purple bg-brand-purple text-white shadow-md' : 'border-border text-muted hover:border-brand-purple hover:text-brand-purple' }}">
                {{ __('app.therapists.filters.' . ($f === 'all' ? 'all' : $f)) }}
            </a>
        @endforeach
    </div>

    @if($therapists->isEmpty())
        <div class="glass-card rounded-2xl py-12 text-center text-muted-light">
            <div class="mb-2 text-3xl">🔍</div>
            <p class="text-sm">{{ __('app.therapists.no_results') }}</p>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($therapists as $therapist)
                @php
                    $bgMap = ['green' => 'bg-brand-green-light', 'purple' => 'bg-brand-purple-light', 'orange' => 'bg-brand-orange-light', 'blue' => 'bg-brand-blue-light'];
                @endphp
                <div class="glass-card rounded-2xl p-5 transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="mb-4 flex gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-xl {{ $bgMap[$therapist->bg_color] ?? 'bg-brand-green-light' }}">{{ $therapist->icon }}</div>
                        <div>
                            <h4 class="text-sm font-bold">{{ $therapist->name }}</h4>
                            <p class="text-[11px] text-muted-light">{{ $therapist->specialization }} • {{ __('app.therapists.years', ['count' => $therapist->years_experience]) }}</p>
                            <p class="text-[11px] text-amber-500">⭐ {{ $therapist->rating }}</p>
                        </div>
                    </div>
                    <div class="mb-4 flex flex-wrap gap-1.5">
                        @foreach($therapist->tags as $tag)
                            <span class="rounded-full bg-brand-purple-light px-2.5 py-0.5 text-[10px] font-bold text-brand-purple">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-brand-green">● {{ $therapist->availability }}</span>
                        <button type="button"
                                @click="modalOpen=true; therapistId={{ $therapist->id }}; therapistName='{{ $therapist->name }}'"
                                class="rounded-full bg-brand-purple px-4 py-1.5 text-xs font-bold text-white shadow transition hover:scale-105">
                            {{ __('app.therapists.book') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Booking modal --}}
    <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-[200] flex items-center justify-center bg-black/50 p-4" @click.self="modalOpen=false">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" @click.stop>
            <h3 class="font-display text-xl font-semibold">{{ __('app.therapists.modal_title') }}</h3>
            <p class="mb-5 text-xs text-muted-light" x-text="@js(__('app.therapists.modal_sub', ['name' => ':name'])).replace(':name', therapistName)"></p>
            <form action="{{ route('therapists.book') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="therapist_id" :value="therapistId">
                <div>
                    <label class="mb-1 block text-[11px] font-bold text-muted">{{ __('app.therapists.name') }}</label>
                    <input type="text" name="contact_name" value="{{ $currentUser->name }}" required class="w-full rounded-xl border border-border bg-surface px-3 py-2.5 text-sm outline-none focus:border-brand-green">
                </div>
                <div>
                    <label class="mb-1 block text-[11px] font-bold text-muted">{{ __('app.therapists.email') }}</label>
                    <input type="email" name="email" required placeholder="email@example.com" class="w-full rounded-xl border border-border bg-surface px-3 py-2.5 text-sm outline-none focus:border-brand-green">
                </div>
                <div>
                    <label class="mb-1 block text-[11px] font-bold text-muted">{{ __('app.therapists.date') }}</label>
                    <input type="date" name="appointment_date" value="{{ now()->toDateString() }}" required class="w-full rounded-xl border border-border bg-surface px-3 py-2.5 text-sm outline-none focus:border-brand-green">
                </div>
                <div>
                    <label class="mb-1 block text-[11px] font-bold text-muted">{{ __('app.therapists.time') }}</label>
                    <select name="appointment_time" class="w-full rounded-xl border border-border bg-surface px-3 py-2.5 text-sm outline-none focus:border-brand-green">
                        @foreach(['10:00 AM', '11:00 AM', '2:00 PM', '3:00 PM', '5:00 PM'] as $time)
                            <option>{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="modalOpen=false" class="rounded-full border border-border px-4 py-2 text-xs font-bold text-muted">{{ __('app.therapists.cancel') }}</button>
                    <button type="submit" class="rounded-full bg-brand-green px-4 py-2 text-xs font-bold text-white">✓ {{ __('app.therapists.confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
