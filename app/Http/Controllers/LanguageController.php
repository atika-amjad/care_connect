<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, ['en', 'ur'], true)) {
            abort(404);
        }

        app()->setLocale($locale);

        if ($userId = $request->session()->get('wellness_user_id')) {
            User::where('id', $userId)->update(['language' => $locale]);
        }

        return back();
    }
}
