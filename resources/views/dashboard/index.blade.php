@extends('layouts.app')

@section('title', __('app.nav.dashboard'))

@php $pageKey = 'dashboard'; @endphp

@section('content')
    {{-- Welcome banner --}}
    <div class="gradient-brand relative mb-6 overflow-hidden rounded-2xl p-6 text-white shadow-xl shadow-brand-green/20">
        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-6xl opacity-15">💚</div>
        <h2 class="font-display text-xl font-semibold sm:text-2xl">{{ $greeting }}</h2>
        <p class="mt-2 max-w-md text-sm leading-relaxed text-white/90">{{ $intro }}</p>
    </div>

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach([
            ['icon' => '📅', 'value' => $stats['streak'], 'label' => __('app.dashboard.streak'), 'bg' => 'bg-brand-green-light'],
            ['icon' => '😊', 'value' => $stats['avg_mood'] ?? '—', 'label' => __('app.dashboard.avg_mood'), 'bg' => 'bg-brand-orange-light'],
            ['icon' => '📓', 'value' => $stats['journal_count'], 'label' => __('app.dashboard.journals'), 'bg' => 'bg-brand-purple-light'],
            ['icon' => '🧘', 'value' => $stats['meditation_count'], 'label' => __('app.dashboard.meditations'), 'bg' => 'bg-brand-blue-light'],
        ] as $stat)
            <div class="glass-card rounded-2xl p-4 transition hover:-translate-y-1 hover:shadow-xl">
                <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl {{ $stat['bg'] }} text-lg">{{ $stat['icon'] }}</div>
                <div class="text-2xl font-bold">{{ $stat['value'] }}</div>
                <div class="mt-1 text-[11px] text-muted-light">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="mb-6 grid gap-4 lg:grid-cols-2">
        {{-- Chart --}}
        <div class="glass-card rounded-2xl p-5">
            <h3 class="mb-4 flex items-center gap-2 text-sm font-bold">📊 {{ __('app.dashboard.week_mood') }}</h3>
            @if($stats['has_moods'])
                <div class="flex h-24 items-end gap-2">
                    @foreach($stats['week_chart'] as $bar)
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <div class="w-full rounded-t-md transition-all duration-500" style="height: {{ max($bar['value'] * 8, 4) }}px; background: {{ $bar['value'] ? $bar['color'] : '#e8eaf3' }}"></div>
                            <span class="text-[9px] text-muted-light">{{ $bar['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-muted-light">
                    <div class="mb-2 text-3xl">📊</div>
                    <p class="text-xs">{{ __('app.dashboard.no_chart') }}</p>
                    <a href="{{ route('mood.index') }}" class="mt-2 inline-block text-xs font-bold text-brand-green">{{ __('app.dashboard.add_mood') }}</a>
                </div>
            @endif
        </div>

        {{-- Quick actions --}}
        <div class="glass-card rounded-2xl p-5">
            <h3 class="mb-4 flex items-center gap-2 text-sm font-bold">⚡ {{ __('app.dashboard.quick_actions') }}</h3>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    ['route' => 'mood.index', 'icon' => '😊', 'key' => 'mood', 'bg' => 'bg-brand-green-light border-green-200'],
                    ['route' => 'journal.index', 'icon' => '✍️', 'key' => 'journal', 'bg' => 'bg-brand-purple-light border-purple-200'],
                    ['route' => 'meditation.index', 'icon' => '🧘', 'key' => 'meditation', 'bg' => 'bg-brand-blue-light border-blue-200'],
                    ['route' => 'therapists.index', 'icon' => '👩‍⚕️', 'key' => 'therapist', 'bg' => 'bg-brand-orange-light border-orange-200'],
                ] as $qa)
                    <a href="{{ route($qa['route']) }}" class="flex items-center gap-3 rounded-xl border p-3 transition hover:-translate-y-1 hover:shadow-md {{ $qa['bg'] }}">
                        <span class="text-xl">{{ $qa['icon'] }}</span>
                        <div>
                            <div class="text-xs font-bold">{{ __('app.dashboard.qa.' . $qa['key'])[0] }}</div>
                            <div class="text-[10px] text-muted-light">{{ __('app.dashboard.qa.' . $qa['key'])[1] }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent moods --}}
    <div class="glass-card rounded-2xl p-5">
        <h3 class="mb-4 flex items-center gap-2 text-sm font-bold">🕒 {{ __('app.dashboard.last_moods') }}</h3>
        @forelse($stats['recent_moods'] as $mood)
            @include('partials.mood-item', ['mood' => $mood])
        @empty
            <div class="py-8 text-center text-muted-light">
                <div class="mb-2 text-3xl">😐</div>
                <p class="text-xs">{{ __('app.dashboard.no_moods') }} <a href="{{ route('mood.index') }}" class="font-bold text-brand-green">{{ __('app.dashboard.add_mood') }}</a></p>
            </div>
        @endforelse
    </div>
@endsection
