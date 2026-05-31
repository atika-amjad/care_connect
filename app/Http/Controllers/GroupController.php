<?php

namespace App\Http\Controllers;

use App\Models\SupportGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(): View
    {
        $user = view()->shared('currentUser');
        $groups = SupportGroup::orderBy('name_en')->get();
        $joinedIds = $user->supportGroups()->pluck('support_groups.id')->toArray();

        return view('groups.index', compact('groups', 'joinedIds'));
    }

    public function toggle(Request $request, SupportGroup $group): RedirectResponse
    {
        $user = view()->shared('currentUser');

        if ($user->supportGroups()->where('support_group_id', $group->id)->exists()) {
            $user->supportGroups()->detach($group->id);
            $message = __('app.groups.left');
        } else {
            $user->supportGroups()->attach($group->id);
            $message = __('app.groups.joined');
        }

        return redirect()->route('groups.index')->with('toast', $message);
    }
}
