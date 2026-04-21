<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080909">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Tobac-Go' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-[#060707] text-white antialiased">
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(38,99,235,0.18),transparent_34%),radial-gradient(circle_at_80%_20%,rgba(14,165,233,0.16),transparent_28%),linear-gradient(180deg,#060707_0%,#090c0d_55%,#050607_100%)]"></div>
        <div class="absolute left-[-12%] top-20 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="absolute bottom-10 right-[-10%] h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(circle_at_center,black,transparent_85%)]"></div>
    </div>

    <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-4 sm:px-6">
        <header class="flex items-center justify-between border-b border-white/10 py-5">
            <a href="{{ route('home') }}" class="shrink-0">
                <img src="{{ asset('logo.webp') }}" class="h-9 lg:h-10" alt="Tobac-Go">
            </a>

            <nav class="hidden items-center gap-2 text-sm text-white/60 sm:flex">
                <a href="{{ route('home') }}" class="rounded-full border border-white/10 px-4 py-2 transition hover:border-white/20 hover:bg-white/5 hover:text-white">Home</a>
                <a href="{{ route('products') }}" class="rounded-full border border-white/10 px-4 py-2 transition hover:border-white/20 hover:bg-white/5 hover:text-white">Shop</a>
                <a href="{{ route('cart') }}" class="rounded-full border border-white/10 px-4 py-2 transition hover:border-white/20 hover:bg-white/5 hover:text-white">Cart</a>
            </nav>
        </header>

        <main class="flex flex-1 items-center py-12 sm:py-16">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
