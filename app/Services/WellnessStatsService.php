<?php

namespace App\Services;

use App\Models\MoodEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WellnessStatsService
{
    public function forUser(User $user): array
    {
        $moods = $user->moodEntries()->orderByDesc('logged_date')->orderByDesc('created_at')->get();
        $journals = $user->journalEntries()->count();

        return [
            'streak' => $this->calculateStreak($moods),
            'avg_mood' => $this->averageMoodLastSevenDays($moods),
            'journal_count' => $journals,
            'meditation_count' => $user->meditation_sessions_count,
            'recent_moods' => $moods->take(3),
            'week_chart' => $this->weekChart($moods),
            'has_moods' => $moods->isNotEmpty(),
        ];
    }

    public function calculateStreak(Collection $moods): int
    {
        if ($moods->isEmpty()) {
            return 0;
        }

        $dates = $moods->pluck('logged_date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->unique()
            ->sortDesc()
            ->values();

        $today = now()->toDateString();

        if ($dates->first() !== $today) {
            return 0;
        }

        $streak = 1;
        for ($i = 1; $i < $dates->count(); $i++) {
            $current = Carbon::parse($dates[$i - 1]);
            $previous = Carbon::parse($dates[$i]);

            if ($current->diffInDays($previous) === 1) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    public function averageMoodLastSevenDays(Collection $moods): ?float
    {
        $week = $moods->filter(fn (MoodEntry $m) => Carbon::parse($m->logged_date)->gte(now()->subDays(7)));

        if ($week->isEmpty()) {
            return null;
        }

        return round($week->avg('score'), 1);
    }

    public function weekChart(Collection $moods): array
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $colors = ['#4CAF84', '#7C6FF7', '#4A9EDF', '#F5924E', '#E05C6A', '#7C6FF7', '#4CAF84'];
        $bars = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayMoods = $moods->filter(fn (MoodEntry $m) => Carbon::parse($m->logged_date)->isSameDay($date));
            $value = $dayMoods->isEmpty() ? 0 : (int) round($dayMoods->avg('score'));
            $dayIndex = $date->dayOfWeekIso - 1;

            $bars[] = [
                'day' => $days[$dayIndex],
                'value' => $value,
                'color' => $colors[$dayIndex],
            ];
        }

        return $bars;
    }

    public function greeting(User $user): string
    {
        $hour = now()->hour;
        $period = match (true) {
            $hour < 12 => __('app.greeting.morning'),
            $hour < 17 => __('app.greeting.afternoon'),
            default => __('app.greeting.evening'),
        };

        return __('app.dashboard.greeting', ['period' => $period, 'name' => $user->name]);
    }
}
