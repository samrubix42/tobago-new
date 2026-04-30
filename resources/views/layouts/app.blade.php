<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Tobac-Go premium hookah ecommerce store for shoppers in India. Explore luxury hookah products, premium setups, and WhatsApp-assisted buying.')">
    <meta name="keywords" content="@yield('meta_keywords', 'premium hookah india, buy hookah online india, Tobac-Go, luxury hookah, premium hookah store')">
    <meta name="theme-color" content="#080909">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('meta_title', $title ?? 'Tobac-Go | Premium Hookah Store India')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes float-in {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .whatsapp-sticky {
            animation: float-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>

<body class="min-h-screen bg-[#060707] text-white">

    <livewire:public.include.header />
    <main class="overflow-hidden">
        {{ $slot }}
    </main>
    <livewire:public.include.footer />

    <!-- WhatsApp Floating Button -->
    @php
    $whatsapp = app_setting('whatsapp_number');
    @endphp

    @if ($whatsapp)
    <div class="fixed bottom-20 right-4 sm:bottom-20 lg:bottom-10 md:right-8 z-[9999] whatsapp-sticky">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank"
            class="relative flex items-center justify-center gap-0 sm:gap-3 bg-gradient-to-br from-[#25D366] to-[#128C7E] text-white h-14 w-14 sm:h-auto sm:w-auto sm:px-5 sm:py-3 md:px-6 md:py-4 rounded-full shadow-[0_10px_30px_rgba(37,211,102,0.3)] transition-all duration-300 hover:scale-105 hover:shadow-[0_15px_40px_rgba(37,211,102,0.4)] group">
            <div class="relative flex items-center justify-center shrink-0">
                <i class="ri-whatsapp-fill text-2xl md:text-3xl transition-transform duration-300 group-hover:rotate-12"></i>
                <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5 md:h-3 md:w-3">
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 md:h-3 md:w-3 bg-white"></span>
                </span>
            </div>
            <div class="hidden sm:flex flex-col border-l border-white/20 pl-3">
                <span class="text-[9px] md:text-[10px] leading-none opacity-80 font-bold uppercase tracking-widest">Have questions?</span>
                <span class="text-xs md:text-sm font-black leading-tight tracking-tight">Chat with us</span>
            </div>
        </a>
    </div>
    @endif

    @livewireScripts
</body>

</html>