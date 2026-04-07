<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Admin Panel | Tobac-Go' }}</title>

    <!-- 🔷 Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- 🔷 Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">

    <!-- 🔷 Assets -->
    @vite(['resources/css/admin.css', 'resources/js/app.js'])

    @livewireStyles
      <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 text-gray-900  antialiased" style="font-family: 'Inter', sans-serif;">

    <div class="flex min-h-screen" x-data="{ mobileMenu: false }" x-cloak>

        <!-- 🔷 Sidebar -->
        <livewire:admin.include.sidebar />

        <!-- 🔷 Main Layout -->
        <div class="flex flex-col flex-1 lg:pl-64"> <!-- FIXED WIDTH -->

            <!-- 🔷 Header -->
            <livewire:admin.include.header :title="$title ?? 'Dashboard'" />

            <!-- 🔷 Page Content -->
            <main class="flex-1 p-6 lg:p-8">
                <div class="max-w-7xl mx-auto w-full">
                    {{ $slot }}
                </div>
            </main>
            @include('components.toast')
        </div>
    </div>

    @livewireScripts

</body>
</html>