<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeditationController extends Controller
{
    private const EXERCISES = [
        ['slug' => 'morning-calm', 'icon' => '🌅', 'duration' => '10 min', 'minutes' => 10, 'level' => 'beginner', 'color' => 'green'],
        ['slug' => 'anxiety-relief', 'icon' => '💆', 'duration' => '15 min', 'minutes' => 15, 'level' => 'guided', 'color' => 'purple'],
        ['slug' => 'deep-sleep', 'icon' => '🌙', 'duration' => '20 min', 'minutes' => 20, 'level' => 'evening', 'color' => 'blue'],
        ['slug' => 'body-scan', 'icon' => '🫀', 'duration' => '12 min', 'minutes' => 12, 'level' => 'healing', 'color' => 'orange'],
        ['slug' => 'focus-boost', 'icon' => '🎯', 'duration' => '8 min', 'minutes' => 8, 'level' => 'work', 'color' => 'green'],
        ['slug' => 'loving-kindness', 'icon' => '💛', 'duration' => '18 min', 'minutes' => 18, 'level' => 'advanced', 'color' => 'purple'],
    ];

    public function index(): View
    {
        $user = view()->shared('currentUser');

        return view('meditation.index', [
            'exercises' => self::EXERCISES,
            'sessionCount' => $user->meditation_sessions_count,
        ]);
    }

    public function startExercise(Request $request, string $slug): RedirectResponse
    {
        if (! collect(self::EXERCISES)->contains('slug', $slug)) {
            abort(404);
        }

        $user = view()->shared('currentUser');
        $user->increment('meditation_sessions_count');

        $exercise = collect(self::EXERCISES)->firstWhere('slug', $slug);

        return redirect()->route('meditation.index')->with(
            'toast',
            __('app.meditation.started', [
                'icon' => $exercise['icon'],
                'name' => __("app.meditation.exercises.{$slug}"),
                'duration' => $exercise['duration'],
            ])
        );
    }
}
