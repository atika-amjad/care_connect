@extends('layouts.app')

@section('title', __('app.nav.meditation'))
@php
    $pageKey = 'meditation';
    $exerciseData = collect($exercises)->map(fn ($e) => [
        ...$e,
        'name' => __("app.meditation.exercises.{$e['slug']}"),
        'description' => __("app.meditation.exercise_desc.{$e['slug']}"),
        'level_label' => __("app.meditation.levels.{$e['level']}"),
        'guided_steps' => __("app.meditation.exercise_steps.{$e['slug']}"),
    ])->values();
    $breathingSteps = [
        'startTitle' => __('app.meditation.start_breathing'),
        'technique' => __('app.meditation.technique'),
        'techniqueHint' => __('app.meditation.technique_hint'),
        'startLabel' => '▶ ' . __('app.meditation.start'),
        'pauseLabel' => '⏸ ' . __('app.meditation.pause'),
        'resumeLabel' => '▶ ' . __('app.meditation.resume'),
        'pausedLabel' => __('app.meditation.paused'),
        'steps' => __('app.meditation.steps'),
        'phaseLabels' => [
            'inhale' => __('app.meditation.phase_inhale'),
            'hold' => __('app.meditation.phase_hold'),
            'exhale' => __('app.meditation.phase_exhale'),
        ],
    ];
    $hubConfig = [
        'exercises' => $exerciseData,
        'labels' => [
            'readyToBegin' => __('app.meditation.ready_to_begin'),
            'startSession' => __('app.meditation.start_session'),
            'stepOf' => __('app.meditation.step_of'),
            'backToBreathing' => __('app.meditation.back_to_breathing'),
            'selectedExercise' => __('app.meditation.selected_exercise'),
            'sessionComplete' => __('app.meditation.session_complete'),
        ],
    ];
    $filters = ['all', 'beginner', 'guided', 'evening', 'healing', 'work', 'advanced'];
@endphp

@section('content')
<div x-data="meditationHub(@js($breathingSteps), @js($hubConfig))">

    {{-- Stats --}}
    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <div class="glass-card rounded-2xl p-4">
            <div class="text-2xl font-bold text-brand-purple">{{ $sessionCount }}</div>
            <div class="text-[11px] font-semibold text-muted-light">{{ __('app.meditation.sessions_completed') }}</div>
        </div>
        <div class="glass-card rounded-2xl p-4">
            <div class="text-2xl font-bold text-brand-green">
                <span x-show="mode === 'guided' && selectedExercise" x-text="active ? (guidedStepIndex + 1) + '/' + guidedSteps().length : '—'"></span>
                <span x-show="mode === 'breathing'" x-text="cycles">0</span>
            </div>
            <div class="text-[11px] font-semibold text-muted-light">
                <span x-show="mode === 'guided' && selectedExercise">{{ __('app.meditation.current_step') }}</span>
                <span x-show="mode === 'breathing'">{{ __('app.meditation.breath_cycles') }}</span>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-4">
            <div class="text-2xl font-bold text-brand-blue" x-text="formatTime(totalSeconds)">00:00</div>
            <div class="text-[11px] font-semibold text-muted-light">{{ __('app.meditation.session_time') }}</div>
        </div>
    </div>

    {{-- Hero --}}
    <div id="meditation-hero" class="relative mb-6 overflow-hidden rounded-3xl border border-purple-100 bg-gradient-to-br from-brand-purple-light via-white to-brand-blue-light p-6 shadow-xl sm:p-10">
        <div class="pointer-events-none absolute -left-10 top-10 h-32 w-32 rounded-full bg-brand-purple/10 meditation-float"></div>
        <div class="pointer-events-none absolute -right-6 bottom-6 h-24 w-24 rounded-full bg-brand-blue/10 meditation-float-delay"></div>

        <div class="relative text-center">
            {{-- Mode badge --}}
            <div class="mb-3 flex flex-wrap items-center justify-center gap-2">
                <span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest"
                      :class="mode === 'guided' ? 'bg-brand-purple text-white' : 'bg-brand-green text-white'"
                      x-text="mode === 'guided' ? @js(__('app.meditation.selected_exercise')) : @js(__('app.meditation.technique'))"></span>
                <button type="button" x-show="mode === 'guided'" @click="backToBreathing()"
                        class="rounded-full border border-border bg-white/90 px-3 py-1 text-[10px] font-bold text-muted transition hover:border-brand-purple hover:text-brand-purple">
                    {{ __('app.meditation.back_to_breathing') }}
                </button>
            </div>

            {{-- Guided: show exercise name when selected --}}
            <template x-if="mode === 'guided' && selectedExercise">
                <div>
                    <h2 class="font-display text-xl font-semibold sm:text-2xl" x-text="selectedExercise.name"></h2>
                    <p class="mx-auto mt-1 max-w-lg text-xs text-muted" x-show="!active && !guidedComplete" x-text="selectedExercise.description"></p>
                    <p class="mt-1 text-[10px] font-bold text-brand-purple" x-text="selectedExercise.duration + ' · ' + selectedExercise.level_label"></p>
                </div>
            </template>

            {{-- Breathing mode header --}}
            <template x-if="mode === 'breathing'">
                <div>
                    <h2 class="font-display text-xl font-semibold sm:text-2xl">{{ __('app.meditation.hero_title') }}</h2>
                    <p class="mx-auto mt-1 max-w-md text-xs text-muted">{{ __('app.meditation.hero_subtitle') }}</p>
                </div>
            </template>

            {{-- Ring + orb --}}
            <div class="relative mx-auto my-8 flex h-52 w-52 items-center justify-center sm:h-56 sm:w-56">
                <svg class="absolute inset-0 h-full w-full -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="54" fill="none" stroke="#e8eaf3" stroke-width="4"/>
                    <circle cx="60" cy="60" r="54" fill="none" stroke="#7C6FF7" stroke-width="4" stroke-linecap="round"
                            :stroke-dasharray="339.292" :stroke-dashoffset="339.292 - (339.292 * ringProgress / 100)"
                            class="transition-all duration-1000 ease-linear"/>
                </svg>
                <div class="meditation-orb relative flex h-32 w-32 flex-col items-center justify-center rounded-full bg-gradient-to-br from-brand-purple to-brand-blue text-white shadow-2xl shadow-brand-purple/30 sm:h-36 sm:w-36"
                     :style="`transform: scale(${ringScale()})`">
                    <span class="text-4xl" x-show="!active || phase === 'idle' || phase === 'paused' || guidedComplete" x-text="heroIcon()"></span>
                    <span class="text-3xl font-bold tabular-nums" x-show="active && !guidedComplete" x-text="secondsLeft"></span>
                    <span class="mt-1 text-[10px] font-bold uppercase tracking-wider opacity-90" x-show="active && mode === 'breathing' && phase !== 'idle' && phase !== 'paused'" x-text="phaseLabel()"></span>
                    <span class="mt-1 text-[10px] font-bold uppercase tracking-wider opacity-90" x-show="active && mode === 'guided'" x-text="stepLabel()"></span>
                </div>
            </div>

            {{-- Current step title & instruction --}}
            <h3 class="font-display text-lg font-semibold transition-all" x-text="title"></h3>
            <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-muted" x-text="subtitle"></p>

            {{-- Step preview (before start) --}}
            <div x-show="mode === 'guided' && selectedExercise && !active && !guidedComplete" class="mx-auto mt-5 max-w-md text-left">
                <p class="mb-2 text-center text-[10px] font-bold uppercase tracking-widest text-muted-light">{{ __('app.meditation.exercises_title') }}</p>
                <ol class="space-y-1.5">
                    <template x-for="(step, i) in guidedSteps()" :key="i">
                        <li class="flex gap-2 rounded-xl border border-border bg-white/70 px-3 py-2 text-[11px]">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-brand-purple-light text-[10px] font-bold text-brand-purple" x-text="i + 1"></span>
                            <span><strong x-text="step.title"></strong> — <span class="text-muted" x-text="step.text"></span></span>
                        </li>
                    </template>
                </ol>
            </div>

            {{-- Breathing phase pills --}}
            <div x-show="mode === 'breathing'" class="mt-5 flex justify-center gap-2">
                @foreach(['inhale', 'hold', 'exhale'] as $p)
                    <span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wide transition-all"
                          :class="phase === '{{ $p }}' ? 'bg-brand-purple text-white shadow-md scale-105' : 'bg-white/80 text-muted border border-border'">
                        {{ __('app.meditation.phase_' . $p) }}
                    </span>
                @endforeach
            </div>

            {{-- Guided step progress dots --}}
            <div x-show="mode === 'guided' && selectedExercise && (active || guidedComplete)" class="mt-5 flex justify-center gap-2">
                <template x-for="(step, i) in guidedSteps()" :key="'dot-'+i">
                    <span class="h-2 rounded-full transition-all"
                          :class="guidedComplete || i < guidedStepIndex ? 'w-6 bg-brand-purple' : (i === guidedStepIndex && active ? 'w-6 bg-brand-purple animate-pulse' : 'w-2 bg-border')"
                          :title="step.title"></span>
                </template>
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex flex-wrap justify-center gap-2">
                <button type="button" @click="heroAction()"
                        class="rounded-full bg-brand-purple px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-brand-purple/30 transition hover:-translate-y-0.5"
                        x-text="buttonLabel"></button>
                <button type="button" @click="resetAll()" x-show="!guidedComplete"
                        class="rounded-full border border-border bg-white/80 px-6 py-2.5 text-xs font-bold text-muted transition hover:bg-white">
                    ↺ {{ __('app.meditation.reset') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Tip --}}
    <div class="mb-6 flex gap-3 rounded-2xl border border-green-200 bg-brand-green-light/60 p-4">
        <span class="text-2xl">💡</span>
        <div>
            <p class="text-xs font-bold text-brand-green">{{ __('app.meditation.tip_title') }}</p>
            <p class="mt-0.5 text-xs leading-relaxed text-muted">{{ __('app.meditation.tip_text') }}</p>
        </div>
    </div>

    {{-- Exercise grid --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h3 class="flex items-center gap-2 text-sm font-bold">🧘 {{ __('app.meditation.exercises_title') }}</h3>
            <p class="mt-0.5 text-xs text-muted-light">{{ __('app.meditation.exercises_subtitle') }}</p>
        </div>
        <div class="flex flex-wrap gap-1.5">
            @foreach($filters as $f)
                <button type="button" @click="filter = '{{ $f }}'"
                        :class="filter === '{{ $f }}' ? 'bg-brand-purple text-white border-brand-purple shadow' : 'bg-white text-muted border-border hover:border-brand-purple/50'"
                        class="rounded-full border px-3 py-1 text-[10px] font-bold transition">
                    {{ $f === 'all' ? __('app.meditation.filter_all') : __('app.meditation.levels.' . $f) }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <template x-for="exercise in filteredExercises()" :key="exercise.slug">
            <button type="button" @click="selectExercise(exercise)"
                    class="group overflow-hidden rounded-2xl border-2 text-left transition hover:-translate-y-1 hover:shadow-xl"
                    :class="isSelected(exercise) ? 'border-brand-purple bg-brand-purple-light/30 shadow-lg' : 'glass-card border-transparent'">
                <div class="flex h-20 items-center justify-center bg-gradient-to-br text-4xl"
                     :class="{
                        'from-brand-green/30 to-brand-green/5': exercise.color === 'green',
                        'from-brand-purple/30 to-brand-purple/5': exercise.color === 'purple',
                        'from-brand-blue/30 to-brand-blue/5': exercise.color === 'blue',
                        'from-brand-orange/30 to-brand-orange/5': exercise.color === 'orange',
                     }">
                    <span x-text="exercise.icon"></span>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="text-sm font-bold" x-text="exercise.name"></h4>
                        <span class="shrink-0 rounded-full bg-surface px-2 py-0.5 text-[10px] font-bold text-muted-light" x-text="exercise.duration"></span>
                    </div>
                    <p class="mt-1.5 line-clamp-2 text-[11px] leading-relaxed text-muted" x-text="exercise.description"></p>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold"
                              :class="{
                                'bg-brand-green-light text-brand-green': exercise.color === 'green',
                                'bg-brand-purple-light text-brand-purple': exercise.color === 'purple',
                                'bg-brand-blue-light text-brand-blue': exercise.color === 'blue',
                                'bg-brand-orange-light text-brand-orange': exercise.color === 'orange',
                              }"
                              x-text="exercise.level_label"></span>
                        <span class="text-[10px] font-bold"
                              :class="isSelected(exercise) ? 'text-brand-purple' : 'text-brand-purple opacity-0 group-hover:opacity-100'"
                              x-text="isSelected(exercise) ? '✓ {{ __('app.meditation.selected_exercise') }}' : '▶ {{ __('app.meditation.start_session') }}'"></span>
                    </div>
                </div>
            </button>
        </template>
    </div>

    <form x-ref="completeForm" method="POST" x-bind:action="selectedExercise ? '{{ url('/meditation') }}/' + selectedExercise.slug : '#'" class="hidden">
        @csrf
    </form>
</div>
@endsection
