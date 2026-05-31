@extends('layouts.app')

@section('title', __('app.nav.mood'))
@php $pageKey = 'mood'; @endphp

@section('content')
<div x-data="moodForm()">
    <form action="{{ route('mood.store') }}" method="POST" class="glass-card mb-6 rounded-2xl p-5" @submit="if(!selected){ $event.preventDefault(); alert(@js(__('app.mood.select_first'))) }">
        @csrf
        <h3 class="mb-4 flex items-center gap-2 text-sm font-bold">🌡️ {{ __('app.mood.title') }}</h3>

        <div class="mb-6 flex flex-wrap justify-around gap-2">
            @foreach($moods as $mood)
                <button type="button" @click="pick(@js($mood))"
                        :class="isSelected(@js($mood)) ? 'scale-110 border-brand-green bg-brand-green-light' : 'border-transparent hover:scale-105'"
                        class="flex flex-col items-center gap-2 rounded-2xl border-2 p-3 transition-all">
                    <span class="text-4xl">{{ $mood['emoji'] }}</span>
                    <span class="text-[10px] font-bold text-muted">{{ $mood['label'] }}</span>
                </button>
            @endforeach
        </div>

        <input type="hidden" name="score" :value="selected?.score ?? ''">
        <input type="hidden" name="emoji" :value="selected?.emoji ?? ''">
        <input type="hidden" name="label" :value="selected?.label ?? ''">

        <div class="mb-4">
            <label class="mb-2 flex justify-between text-xs font-bold text-muted">
                <span>{{ __('app.mood.energy') }}</span>
                <span id="energyVal" class="rounded-full bg-brand-green px-2 py-0.5 text-[11px] text-white">5</span>
            </label>
            <input type="range" name="energy" min="1" max="10" value="5" oninput="document.getElementById('energyVal').textContent=this.value">
        </div>

        <div class="mb-4">
            <label class="mb-2 flex justify-between text-xs font-bold text-muted">
                <span>{{ __('app.mood.anxiety') }}</span>
                <span id="anxietyVal" class="rounded-full bg-brand-green px-2 py-0.5 text-[11px] text-white">5</span>
            </label>
            <input type="range" name="anxiety" min="1" max="10" value="5" oninput="document.getElementById('anxietyVal').textContent=this.value">
        </div>

        <p class="mb-2 text-xs font-bold text-muted">{{ __('app.mood.tags_label') }}</p>
        <div class="mb-4 flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <button type="button" @click="toggleTag(@js(__('app.mood.tags.' . $tag)))"
                        :class="hasTag(@js(__('app.mood.tags.' . $tag))) ? 'bg-brand-green text-white border-brand-green' : 'bg-surface text-muted border-border'"
                        class="rounded-full border px-3 py-1 text-[11px] font-bold transition">
                    {{ __('app.mood.tags.' . $tag) }}
                </button>
                <template x-if="hasTag(@js(__('app.mood.tags.' . $tag)))">
                    <input type="hidden" name="tags[]" value="{{ __('app.mood.tags.' . $tag) }}">
                </template>
            @endforeach
        </div>

        <textarea name="note" rows="3" placeholder="{{ __('app.mood.note_placeholder') }}"
                  class="w-full rounded-xl border border-border bg-surface px-4 py-3 text-sm outline-none focus:border-brand-green focus:ring-4 focus:ring-brand-green/20"></textarea>

        <div class="mt-4">
            <button type="submit" :disabled="!selected"
                    class="rounded-full bg-brand-green px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-green/30 transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-50">
                💾 {{ __('app.mood.save') }}
            </button>
        </div>
    </form>

    <h3 class="mb-3 flex items-center gap-2 text-sm font-bold">📋 {{ __('app.mood.history') }}</h3>
    @forelse($history as $entry)
        @include('partials.mood-item', ['mood' => $entry])
    @empty
        <div class="glass-card rounded-2xl py-10 text-center text-muted-light">
            <div class="mb-2 text-3xl">📋</div>
            <p class="text-xs">{{ __('app.mood.no_record') }}</p>
        </div>
    @endforelse
</div>
@endsection
