<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWellnessUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('wellness_user_id');

        if (! $userId || ! User::find($userId)) {
            return redirect()->route('setup');
        }

        $user = User::findOrFail($userId);
        app()->setLocale($user->language ?? 'en');
        view()->share('currentUser', $user);

        return $next($request);
    }
}
