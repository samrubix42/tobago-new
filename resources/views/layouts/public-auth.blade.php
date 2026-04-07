<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Account | Tobac-Go' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[#09090b] text-white antialiased" style="font-family: 'Inter', sans-serif;">

    <div class="flex min-h-screen w-full">

        {{-- ── LEFT: Form Panel ── --}}
        <div class="flex flex-col justify-center w-full md:w-1/2 xl:w-5/12 min-h-screen px-8 py-12 lg:px-14 bg-[#09090b] overflow-y-auto">
            {{ $slot }}
        </div>

        {{-- ── RIGHT: Image Panel (hidden on phones) ── --}}
        <div class="hidden md:flex md:w-1/2 xl:w-7/12 relative overflow-hidden flex-shrink-0">

            {{-- Background image --}}
            <img src="{{ asset('auth-panel.webp') }}"
                alt="Tobac-Go Premium"
                class="absolute inset-0 w-full h-full object-cover object-center">

            {{-- Dark gradient overlay --}}
            <div class="absolute inset-0"
                style="background: linear-gradient(to bottom right, rgba(9,9,11,0.55) 0%, rgba(9,9,11,0.30) 50%, rgba(9,9,11,0.70) 100%);"></div>

            {{-- Content overlay --}}
            <div class="relative z-10 flex flex-col justify-end p-10 w-full">

                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/15 bg-white/8 backdrop-blur-md text-xs text-white/75 w-fit mb-4">
                    <i class="ri-shield-check-line text-white/60"></i>
                    Trusted by 50,000+ customers
                </div>

                <h2 class="text-3xl xl:text-4xl font-bold leading-tight tracking-tight mb-3">
                    Premium Flavours,<br>
                    <span class="text-white/60">Delivered to Your Door.</span>
                </h2>

                <p class="text-sm text-white/45 leading-relaxed mb-6 max-w-sm">
                    Explore our curated collection of the finest shisha, hookah, and tobacco products from around the world.
                </p>

                <div class="flex flex-wrap gap-2">
                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full border border-white/10 bg-black/30 backdrop-blur text-xs text-white/55">
                        <i class="ri-truck-line text-white/40"></i> Free Delivery
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full border border-white/10 bg-black/30 backdrop-blur text-xs text-white/55">
                        <i class="ri-medal-line text-white/40"></i> Authentic Brands
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full border border-white/10 bg-black/30 backdrop-blur text-xs text-white/55">
                        <i class="ri-secure-payment-line text-white/40"></i> Secure Checkout
                    </span>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
</body>

</html>