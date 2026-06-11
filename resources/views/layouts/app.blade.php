<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WWM6LWR8');</script>
    <!-- End Google Tag Manager -->

    @php
        $currentPath = request()->path();
        $seoSlug = ($currentPath === '/') ? '/' : trim($currentPath, '/');
        $seoContent = \App\Models\SeoContent::where('page_slug', $seoSlug)->first();
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', $seoContent && !empty($seoContent->meta_description) ? $seoContent->meta_description : ($metaDescription ?? 'Tobac-Go premium hookah ecommerce store for shoppers in India. Explore luxury hookah products, premium setups, and WhatsApp-assisted buying.'))">
    <meta name="keywords" content="@yield('meta_keywords', $seoContent && !empty($seoContent->meta_keywords) ? $seoContent->meta_keywords : ($metaKeywords ?? 'premium hookah india, buy hookah online india, Tobac-Go, luxury hookah, premium hookah store'))">
    <meta name="theme-color" content="#080909">
    <meta name="robots" content="index,follow">
    @php
        $canonicalBase = 'https://www.tobacgo.in';
        $canonicalPath = request()->path();
        $canonicalUrl = rtrim($canonicalBase, '/') . '/' . ltrim($canonicalPath, '/');
    @endphp
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

    <title>@yield('meta_title', $seoContent && !empty($seoContent->meta_title) ? $seoContent->meta_title : ($title ?? 'Tobac-Go | Premium Hookah Store India'))</title>
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
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWM6LWR8"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <livewire:public.include.header />
    <main class="overflow-hidden">
        {{ $slot }}
    </main>
    <livewire:public.include.footer />

    <!-- WhatsApp Floating Button -->
    @php
    $whatsapp = app_setting('whatsapp_number');
    $isCartOrPaymentPage = request()->routeIs('cart', 'order.checkout', 'payment.*') || request()->is('cart', 'checkout', 'payment/*');
    @endphp

    @if ($whatsapp && !$isCartOrPaymentPage)
    <div class="fixed bottom-20 right-4 sm:bottom-20 lg:bottom-10 md:right-8 z-[9999] whatsapp-sticky">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener noreferrer"
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

    @include('components.premium-toast')

    @livewireScripts
</body>

</html>