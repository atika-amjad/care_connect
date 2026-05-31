<?php

namespace App\Http\Controllers;

use App\Services\WellnessStatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private WellnessStatsService $stats)
    {
    }

    public function index(): View
    {
        $user = view()->shared('currentUser');
        $stats = $this->stats->forUser($user);

        return view('dashboard.index', [
            'stats' => $stats,
            'greeting' => $this->stats->greeting($user),
            'intro' => $stats['has_moods'] ? __('app.dashboard.intro_active') : __('app.dashboard.intro'),
        ]);
    }
}
