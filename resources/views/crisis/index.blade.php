@extends('layouts.app')

@section('title', __('app.nav.crisis'))
@php $pageKey = 'crisis'; @endphp

@section('content')
<div class="gradient-crisis mb-6 rounded-2xl p-6 text-white shadow-xl">
    <h2 class="font-display text-xl font-semibold">{{ __('app.crisis.title') }}</h2>
    <p class="mt-2 max-w-lg text-xs leading-relaxed text-white/90">{{ __('app.crisis.intro') }}</p>
</div>

<div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($helplines as $line)
        <div class="glass-card rounded-2xl p-5">
            <div class="mb-2 text-2xl">{{ $line['icon'] }}</div>
            <h4 class="text-xs font-bold">{{ $line['name'] }}</h4>
            <p class="mt-1 text-lg font-bold text-brand-red">{{ $line['number'] }}</p>
            <p class="text-[10px] text-muted-light">{{ $line['hours'] }}</p>
            <button type="button" onclick="alert(@js(__('app.crisis.connecting')))"
                    class="mt-4 w-full rounded-full bg-brand-red py-2 text-xs font-bold text-white transition hover:bg-red-600">
                {{ $line['type'] === 'chat' ? __('app.crisis.chat') : __('app.crisis.call') }}
            </button>
        </div>
    @endforeach
</div>

<div class="glass-card rounded-2xl p-5">
    <h3 class="mb-4 flex items-center gap-2 text-sm font-bold">🧘 {{ __('app.crisis.coping') }}</h3>
    <div class="grid gap-3 sm:grid-cols-2">
        <a href="{{ route('meditation.index') }}" class="rounded-xl border border-purple-200 bg-brand-purple-light p-4 transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="mb-1 text-xl">🌬️</div>
            <div class="text-xs font-bold text-brand-purple">{{ __('app.crisis.breathing') }}</div>
            <div class="text-[10px] text-muted-light">{{ __('app.crisis.breathing_tip') }}</div>
        </a>
        <a href="{{ route('journal.index') }}" class="rounded-xl border border-green-200 bg-brand-green-light p-4 transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="mb-1 text-xl">📓</div>
            <div class="text-xs font-bold text-brand-green">{{ __('app.crisis.journal') }}</div>
            <div class="text-[10px] text-muted-light">{{ __('app.crisis.journal_tip') }}</div>
        </a>
    </div>
</div>
@endsection
