<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Therapist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TherapistController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'all');
        $therapists = Therapist::active()->withTag($filter)->orderBy('name')->get();
        $filters = ['all', 'Anxiety', 'Depression', 'Trauma', 'Relationships', 'Online'];

        return view('therapists.index', compact('therapists', 'filter', 'filters'));
    }

    public function book(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'therapist_id' => ['required', 'exists:therapists,id'],
            'contact_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'string', 'max:16'],
        ]);

        $user = view()->shared('currentUser');

        Appointment::create([
            'user_id' => $user->id,
            ...$validated,
        ]);

        return redirect()->route('therapists.index')->with('toast', __('app.booking.success'));
    }
}
