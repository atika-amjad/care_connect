<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CrisisController extends Controller
{
    public function index(): View
    {
        $helplines = [
            ['icon' => '🇵🇰', 'name' => 'Umang Pakistan', 'number' => '0317-4288665', 'hours' => __('app.crisis.hours.umang'), 'type' => 'call'],
            ['icon' => '🏥', 'name' => 'Rescue Emergency', 'number' => '1122', 'hours' => __('app.crisis.hours.rescue'), 'type' => 'call'],
            ['icon' => '💬', 'name' => 'Rozan Counselling', 'number' => '051-2890505', 'hours' => __('app.crisis.hours.rozan'), 'type' => 'call'],
            ['icon' => '🌐', 'name' => 'iCall (Online Chat)', 'number' => 'icallhelpline.org', 'hours' => __('app.crisis.hours.icall'), 'type' => 'chat'],
        ];

        return view('crisis.index', compact('helplines'));
    }
}
