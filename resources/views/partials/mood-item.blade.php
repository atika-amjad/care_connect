@php
    $color = \App\Models\MoodEntry::scoreColor(
        collect([1, 3, 5, 7, 10])->sortBy(fn ($s) => abs($s - $mood->score))->first()
    );
@endphp
<div class="mb-2 flex items-center gap-3 rounded-xl border border-border bg-white p-3 transition hover:shadow-md">
    <span class="text-2xl">{{ $mood->emoji }}</span>
    <div class="min-w-0 flex-1">
        <div class="text-xs font-bold">{{ $mood->label }} — {{ __('app.mood.energy') }} {{ $mood->energy }}, {{ __('app.mood.anxiety') }} {{ $mood->anxiety }}</div>
        <div class="truncate text-[11px] text-muted-light">
            @if(count($mood->tags ?? []))
                {{ implode(', ', $mood->tags) }}
            @else
                {{ $mood->note ?: '—' }}
            @endif
        </div>
    </div>
    <div class="shrink-0 text-right">
        <div class="text-[10px] text-muted-light">{{ $mood->logged_date->isToday() ? 'Today' : $mood->logged_date->format('M j') }}, {{ $mood->logged_time }}</div>
        <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-[10px] font-bold" style="background: {{ $color }}22; color: {{ $color }}">{{ $mood->label }}</span>
    </div>
</div>
