@extends('layouts.app')

@section('title', __('app.nav.journal'))
@php $pageKey = 'journal'; @endphp

@section('content')
<div x-data="journalForm(@js(__('app.journal.words')))">
    <form action="{{ route('journal.store') }}" method="POST" class="glass-card mb-6 rounded-2xl p-5">
        @csrf
        <div class="mb-4 flex items-center justify-between">
            <h3 class="flex items-center gap-2 text-sm font-bold">✍️ {{ __('app.journal.new_entry') }}</h3>
            <span class="text-[10px] text-muted-light">{{ now()->translatedFormat('l, F j, Y') }}</span>
        </div>

        <div class="mb-3 flex flex-wrap gap-2">
            @foreach($prompts as $prompt)
                <button type="button" @click="addPrompt(@js(__('app.journal.prompts.' . $prompt['key'])))"
                        class="rounded-lg border border-border bg-surface px-3 py-1.5 text-[11px] font-bold text-muted transition hover:border-brand-green hover:bg-brand-green-light hover:text-brand-green">
                    {{ $prompt['emoji'] }} {{ __('app.journal.prompt_labels.' . $prompt['key']) }}
                </button>
            @endforeach
        </div>

        <textarea name="content" x-model="content" @input="updateCount()" required rows="8"
                  placeholder="{{ __('app.journal.placeholder') }}"
                  class="w-full rounded-xl border border-border px-4 py-4 font-display text-sm leading-relaxed outline-none transition focus:border-brand-purple focus:ring-4 focus:ring-brand-purple/20"></textarea>

        <div class="mt-3 flex items-center justify-between">
            <span class="text-[11px] text-muted-light" x-text="label()"></span>
            <button type="submit" class="rounded-full bg-brand-purple px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-purple/30 transition hover:-translate-y-0.5">
                📓 {{ __('app.journal.save') }}
            </button>
        </div>
    </form>

    <h3 class="mb-3 flex items-center gap-2 text-sm font-bold">📚 {{ __('app.journal.past_entries') }}</h3>
    @forelse($entries as $entry)
        <div class="glass-card mb-2 rounded-xl p-4 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="mb-2 flex items-start justify-between gap-3">
                <h4 class="text-sm font-bold">{{ $entry->title }}</h4>
                <span class="shrink-0 text-[10px] text-muted-light">{{ $entry->created_at->format('M j, h:i A') }}</span>
            </div>
            <p class="text-xs leading-relaxed text-muted">{{ Str::limit($entry->content, 110) }}</p>
        </div>
    @empty
        <div class="glass-card rounded-2xl py-10 text-center text-muted-light">
            <div class="mb-2 text-3xl">📚</div>
            <p class="text-xs">{{ __('app.journal.empty') }}</p>
        </div>
    @endforelse
</div>
@endsection
