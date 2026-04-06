<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Account | Tobac-Go' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-[#060707] text-white antialiased">
    <div class="relative min-h-screen flex items-center justify-center px-4 py-14 overflow-hidden">

        <!-- Background glow to match home page -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[10%] w-[420px] h-[420px] opacity-25 blur-[130px]"
                 style="background: radial-gradient(circle, #00c6ff, transparent 60%);">
            </div>
            <div class="absolute top-[8%] right-[10%] w-[420px] h-[420px] opacity-25 blur-[130px]"
                 style="background: radial-gradient(circle, #6a5cff, transparent 60%);">
            </div>
            <div class="absolute bottom-[-12%] left-1/2 -translate-x-1/2 w-[520px] h-[420px] opacity-25 blur-[150px]"
                 style="background: radial-gradient(circle, #ff00cc, transparent 60%);">
            </div>
        </div>

        <div class="relative w-full max-w-md">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>

