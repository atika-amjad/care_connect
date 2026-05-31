<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SetupController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('wellness_user_id')) {
            return redirect()->route('dashboard');
        }

        return view('setup.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'language' => app()->getLocale(),
        ]);

        $request->session()->put('wellness_user_id', $user->id);

        return redirect()->route('dashboard')->with('toast', __('app.messages.welcome'));
    }
}
