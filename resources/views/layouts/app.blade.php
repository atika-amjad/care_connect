<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.brand')) — {{ __('app.brand') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&family=Lora:ital,wght@0,500;0,600;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen" x-data="{ sidebarOpen: false, toast: @js(session('toast')), showToast: @js(session()->has('toast')) }" x-init="if(showToast){ setTimeout(()=>showToast=false, 3500) }">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-border bg-white transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="border-b border-border p-5">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-green to-brand-purple text-lg shadow-md">🌿</div>
                    <span class="font-display text-lg font-semibold">{{ __('app.brand') }}</span>
                </div>
                <div class="flex items-center gap-3 rounded-xl bg-brand-green-light p-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-green text-sm font-bold text-white shadow">
                        {{ $currentUser->initials() }}
                    </div>
                    <div class="min-w-0">
                        <div class="truncate text-sm font-bold">{{ $currentUser->name }}</div>
                        <div class="text-[10px] uppercase tracking-wide text-muted-light">{{ __('app.role') }}</div>
                    </div>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-3">
                <p class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-muted-light">{{ __('app.nav.main') }}</p>
                @foreach([
                    ['route' => 'dashboard', 'icon' => '🏠', 'label' => 'dashboard', 'crisis' => false],
                    ['route' => 'mood.index', 'icon' => '😊', 'label' => 'mood', 'crisis' => false],
                    ['route' => 'journal.index', 'icon' => '📓', 'label' => 'journal', 'crisis' => false],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs($item['route']) ? 'nav-active shadow-sm' : 'text-muted hover:bg-surface hover:text-slate-900' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg text-sm transition-all {{ request()->routeIs($item['route']) ? 'bg-brand-green text-white' : 'bg-border group-hover:bg-brand-green-light' }}">{{ $item['icon'] }}</span>
                        {{ __('app.nav.' . $item['label']) }}
                    </a>
                @endforeach

                <p class="mt-4 px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-muted-light">{{ __('app.nav.support') }}</p>
                @foreach([
                    ['route' => 'therapists.index', 'icon' => '👩‍⚕️', 'label' => 'therapists'],
                    ['route' => 'meditation.index', 'icon' => '🧘', 'label' => 'meditation'],
                    ['route' => 'groups.index', 'icon' => '🤝', 'label' => 'groups'],
                    ['route' => 'crisis.index', 'icon' => '🆘', 'label' => 'crisis', 'crisis' => true],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs($item['route']) ? (($item['crisis'] ?? false) ? 'nav-active-crisis shadow-sm' : 'nav-active shadow-sm') : 'text-muted hover:bg-surface hover:text-slate-900' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg text-sm transition-all {{ request()->routeIs($item['route']) ? (($item['crisis'] ?? false) ? 'bg-brand-red text-white' : 'bg-brand-green text-white') : 'bg-border group-hover:bg-brand-green-light' }}">{{ $item['icon'] }}</span>
                        {{ __('app.nav.' . $item['label']) }}
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- Overlay --}}
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen=false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

        {{-- Main --}}
        <main class="min-h-screen flex-1 lg:ml-64">
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
                {{-- Topbar --}}
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-white text-lg shadow-sm lg:hidden">☰</button>
                        <div>
                            @php $pageKey = $pageKey ?? 'dashboard'; @endphp
                            <h1 class="font-display text-2xl font-semibold">{{ __('app.pages.' . $pageKey)[0] }}</h1>
                            <p class="text-xs text-muted-light">{{ __('app.pages.' . $pageKey)[1] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center rounded-full border-2 border-brand-green bg-brand-green-light p-0.5">
                            <form action="{{ route('language.switch', 'en') }}" method="POST">
                                @csrf
                                <button class="rounded-full px-3 py-1.5 text-xs font-bold transition-all {{ app()->getLocale() === 'en' ? 'bg-brand-green text-white shadow' : 'text-muted' }}">EN</button>
                            </form>
                            <form action="{{ route('language.switch', 'ur') }}" method="POST">
                                @csrf
                                <button class="rounded-full px-3 py-1.5 text-xs font-bold transition-all {{ app()->getLocale() === 'ur' ? 'bg-brand-green text-white shadow' : 'text-muted' }}">اردو</button>
                            </form>
                        </div>
                        <button type="button" @click="toast=@js(__('app.messages.notifications')); showToast=true; setTimeout(()=>showToast=false,3000)" class="flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-white shadow-sm transition hover:shadow">🔔</button>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-green text-sm font-bold text-white shadow">{{ $currentUser->initials() }}</div>
                    </div>
                </div>

                <div class="animate-fade-up">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    {{-- Toast --}}
    <div x-show="showToast" x-transition
         class="fixed bottom-6 right-6 z-[999] rounded-full bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-xl"
         x-text="toast"></div>

    @stack('scripts')
</body>
</html>
