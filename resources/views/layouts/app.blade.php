<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tobac-Go premium hookah ecommerce store for shoppers in India. Explore luxury hookah products, premium setups, and WhatsApp-assisted buying.">
    <meta name="keywords" content="premium hookah india, buy hookah online india, Tobac-Go, luxury hookah, premium hookah store">
    <meta name="theme-color" content="#080909">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'Tobac-Go | Premium Hookah Store India' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-[#060707] text-white">

    <livewire:public.include.header />
    <main class="overflow-hidden">
        {{ $slot }}
    </main>
    <livewire:public.include.footer />


    @livewireScripts
</body>

</html>
