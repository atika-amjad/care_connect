<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View
    {
        $user = view()->shared('currentUser');
        $entries = $user->journalEntries()->latest()->get();

        return view('journal.index', [
            'entries' => $entries,
            'prompts' => [
                ['key' => 'gratitude', 'emoji' => '💚'],
                ['key' => 'challenge', 'emoji' => '💭'],
                ['key' => 'tomorrow', 'emoji' => '🌅'],
                ['key' => 'happiness', 'emoji' => '😊'],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:1'],
        ]);

        $content = trim($validated['content']);
        $words = str_word_count($content);
        $title = str($content)->before('.')->before('!')->before('?')->before("\n")->limit(50)->toString() ?: __('app.journal.untitled');

        $user = view()->shared('currentUser');

        JournalEntry::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
            'word_count' => $words,
        ]);

        return redirect()->route('journal.index')->with('toast', __('app.journal.saved'));
    }
}
