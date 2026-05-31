@extends('layouts.app')

@section('title', __('app.nav.groups'))
@php $pageKey = 'groups'; @endphp

@section('content')
<div class="grid gap-4 md:grid-cols-2">
    @foreach($groups as $group)
        @php
            $joined = in_array($group->id, $joinedIds);
            $bgMap = ['green' => 'bg-brand-green-light', 'purple' => 'bg-brand-purple-light', 'orange' => 'bg-brand-orange-light', 'blue' => 'bg-brand-blue-light'];
        @endphp
        <div class="glass-card overflow-hidden rounded-2xl transition hover:-translate-y-1 hover:shadow-xl">
            <div class="flex h-16 items-center justify-center text-3xl {{ $bgMap[$group->bg_color] ?? 'bg-brand-green-light' }}">{{ $group->icon }}</div>
            <div class="p-5">
                <h4 class="text-sm font-bold">{{ $group->localizedName() }}</h4>
                <p class="mt-1 text-[11px] leading-relaxed text-muted">{{ $group->localizedDescription() }}</p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-[11px] text-muted-light">👥 {{ __('app.groups.members', ['count' => number_format($group->member_count)]) }}</span>
                    <form action="{{ route('groups.toggle', $group) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full border px-4 py-1.5 text-xs font-bold transition {{ $joined ? 'border-brand-green bg-brand-green text-white' : 'border-brand-green text-brand-green hover:bg-brand-green hover:text-white' }}">
                            {{ $joined ? __('app.groups.joined') : __('app.groups.join') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
