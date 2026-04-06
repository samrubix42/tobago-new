<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Login | Tobac-Go' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Assets -->
    @vite(['resources/css/admin.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50 antialiased">

    <div class="min-h-screen flex items-center justify-center px-4">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>