<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'en';

        if ($userId = $request->session()->get('wellness_user_id')) {
            $user = User::find($userId);
            $locale = $user?->language ?? $request->session()->get('locale', 'en');
        }

        if (in_array($locale, ['en', 'ur'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
