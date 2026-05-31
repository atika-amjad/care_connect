<?php

namespace App\Http\Controllers;

use App\Models\MoodEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MoodController extends Controller
{
    private const MOODS = [
        ['score' => 1, 'emoji' => '😫', 'key' => 'very_bad'],
        ['score' => 3, 'emoji' => '😔', 'key' => 'sad'],
        ['score' => 5, 'emoji' => '😐', 'key' => 'okay'],
        ['score' => 7, 'emoji' => '😊', 'key' => 'good'],
        ['score' => 10, 'emoji' => '🤩', 'key' => 'great'],
    ];

    private function localizedMoods(): array
    {
        return array_map(fn (array $m) => [
            ...$m,
            'label' => __('app.mood.levels.'.$m['key']),
        ], self::MOODS);
    }

    private const TAGS = [
        'calm', 'anxiety', 'gratitude', 'tiredness', 'hope',
        'stress', 'happiness', 'loneliness', 'excitement', 'emotional',
    ];

    public function index(): View
    {
        $user = view()->shared('currentUser');
        $history = $user->moodEntries()->latest()->get();

        return view('mood.index', [
            'moods' => $this->localizedMoods(),
            'tags' => self::TAGS,
            'history' => $history,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'score' => ['required', 'integer', 'in:1,3,5,7,10'],
            'emoji' => ['required', 'string', 'max:8'],
            'label' => ['required', 'string', 'max:50'],
            'energy' => ['required', 'integer', 'min:1', 'max:10'],
            'anxiety' => ['required', 'integer', 'min:1', 'max:10'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:30'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = view()->shared('currentUser');

        MoodEntry::create([
            'user_id' => $user->id,
            'score' => $validated['score'],
            'emoji' => $validated['emoji'],
            'label' => $validated['label'],
            'energy' => $validated['energy'],
            'anxiety' => $validated['anxiety'],
            'tags' => $validated['tags'] ?? [],
            'note' => $validated['note'] ?? null,
            'logged_date' => now()->toDateString(),
            'logged_time' => now()->format('h:i A'),
        ]);

        return redirect()->route('mood.index')->with('toast', __('app.mood.saved'));
    }
}
