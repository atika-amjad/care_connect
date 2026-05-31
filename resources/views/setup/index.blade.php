<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.setup.title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&family=Lora:ital,wght@0,500;0,600;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="gradient-setup flex min-h-screen items-center justify-center p-4">
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-8 shadow-2xl animate-fade-up">
        <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-brand-green/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-brand-purple/10 blur-2xl"></div>

        <div class="relative text-center">
            <div class="mb-4 text-5xl">🌿</div>
            <h1 class="font-display text-3xl font-semibold">{{ __('app.setup.title') }}</h1>
            <p class="mt-2 text-sm leading-relaxed text-muted">{{ __('app.setup.intro') }}</p>

            <div class="mt-6 flex justify-center gap-2">
                <form action="{{ route('language.switch', 'en') }}" method="POST">@csrf<button class="rounded-full px-3 py-1 text-xs font-bold {{ app()->getLocale()==='en' ? 'bg-brand-green text-white' : 'bg-surface text-muted' }}">EN</button></form>
                <form action="{{ route('language.switch', 'ur') }}" method="POST">@csrf<button class="rounded-full px-3 py-1 text-xs font-bold {{ app()->getLocale()==='ur' ? 'bg-brand-green text-white' : 'bg-surface text-muted' }}">اردو</button></form>
            </div>

            <form action="{{ route('setup.store') }}" method="POST" class="mt-6 space-y-4 text-left">
                @csrf
                <input type="text" name="name" maxlength="30" required value="{{ old('name') }}"
                       placeholder="{{ __('app.setup.placeholder') }}"
                       class="w-full rounded-xl border-2 border-border bg-surface px-4 py-3.5 text-sm outline-none transition focus:border-brand-green focus:ring-4 focus:ring-brand-green/20">
                @error('name')<p class="text-xs font-semibold text-brand-red">{{ $message }}</p>@enderror
                <button type="submit" class="w-full rounded-xl bg-brand-green py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-green/30 transition hover:-translate-y-0.5 hover:bg-brand-green-dark">
                    {{ __('app.setup.button') }} →
                </button>
            </form>
        </div>
    </div>
</body>
</html>
