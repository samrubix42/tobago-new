
<div class="relative overflow-hidden"
    x-data="{
        lightboxOpen: false,
        videoOpen: false,
        lightboxIndex: 0,
        currentVideoId: null,
        galleryCurrent: 0,
        galleryGroups: [],
        galleryInterval: null,
        gallerySlides: [
            { src: 'hookah-shop-noida.webp', alt: 'Tobac-Go Noida entrance' },
            { src: 'hookah-store.webp', alt: 'Tobac-Go hookah store' },
            { src: 'IMG_20260128_140523102_HDR_AE.webp', alt: 'Tobac-Go store interior' },
            { src: 'IMG_20260128_140531463_HDR_AE.webp', alt: 'Tobac-Go product display' },
            { src: 'IMG_20260128_140539165_HDR_AE.webp', alt: 'Tobac-Go hookah setup' },
            { src: 'IMG_20260128_140551369_HDR_AE.webp', alt: 'Tobac-Go premium hookahs' },
            { src: 'IMG_20260128_140556189_HDR_AE.webp', alt: 'Tobac-Go lounge display' },
            { src: 'IMG_20260128_140604285_HDR_AE.webp', alt: 'Tobac-Go glass hookah' },
            { src: 'IMG_20260128_140619437_HDR_AE.webp', alt: 'Tobac-Go modern hookah' },
            { src: 'IMG_20260128_140636271_HDR_AE.webp', alt: 'Tobac-Go shop showcase' },
            { src: 'IMG_20260128_140657294_HDR_AE.webp', alt: 'Tobac-Go customer area' },
            { src: 'IMG_20260128_140704649_HDR_AE.webp', alt: 'Tobac-Go seating arrangement' },
            { src: 'IMG_20260128_140725260_HDR_AE.webp', alt: 'Tobac-Go store lights' },
            { src: 'IMG_20260128_140736991_HDR_AE.webp', alt: 'Tobac-Go hookah bottles' },
            { src: 'IMG_20260128_140741209_HDR_AE.webp', alt: 'Tobac-Go shop display' },
            { src: 'IMG_20260128_140806546_HDR_AE.webp', alt: 'Tobac-Go hall lighting' },
            { src: 'IMG_20260128_143117876_HDR_AE.webp', alt: 'Tobac-Go store entry' },
            { src: 'IMG_20260128_143142984_HDR_AE.webp', alt: 'Tobac-Go hookah selection' },
            { src: 'IMG_20260128_143230058_HDR_AE.webp', alt: 'Tobac-Go seating and products' },
            { src: 'IMG_20260128_143454966_HDR_AE.webp', alt: 'Tobac-Go interior details' },
            { src: 'IMG_20260128_143611847_HDR_AE.webp', alt: 'Tobac-Go store shelves' },
            { src: 'IMG_20260128_143755140_HDR_AE.webp', alt: 'Tobac-Go shop aisle' },
            { src: 'IMG_20260128_143855798_HDR_AE.webp', alt: 'Tobac-Go in-store display' },
            { src: 'luxury-hookah-design.webp', alt: 'Tobac-Go luxury hookah design' },
            { src: 'premium lifestyle product.webp', alt: 'Tobac-Go premium lifestyle product' },
            { src: 'premium-hookah-design.webp', alt: 'Tobac-Go premium hookah design' },
            { src: 'premium-hookah.webp', alt: 'Tobac-Go premium hookah' },
            { src: 'spaceship hookah.webp', alt: 'Tobac-Go spaceship hookah' },
            { src: 'tobac-go-noida.webp', alt: 'Tobac-Go Noida storefront' }
        ],
        videos: [
            { id: 'h1D9BAqBSKQ', title: 'Tobac-Go Noida Store Video 1', thumbnail: 'https://i.ytimg.com/vi/h1D9BAqBSKQ/mqdefault.jpg' },
            { id: '2FfETJDmAFo', title: 'Tobac-Go Hookah Experience', thumbnail: 'https://i.ytimg.com/vi/2FfETJDmAFo/mqdefault.jpg' },
            { id: 'bjdWZgTY2yU', title: 'Tobac-Go Shop Walkthrough', thumbnail: 'https://i.ytimg.com/vi/bjdWZgTY2yU/mqdefault.jpg' },
            { id: '3gTQ_qXGw1E', title: 'Tobac-Go Product Showcase', thumbnail: 'https://i.ytimg.com/vi/3gTQ_qXGw1E/mqdefault.jpg' },
            { id: 'yYD4niTNUcM', title: 'Tobac-Go Hookah Setup', thumbnail: 'https://i.ytimg.com/vi/yYD4niTNUcM/mqdefault.jpg' },
            { id: 'AdzOhh3sA7M', title: 'Tobac-Go Store Highlights', thumbnail: 'https://i.ytimg.com/vi/AdzOhh3sA7M/mqdefault.jpg' }
        ],
        openLightbox(index) {
            this.lightboxIndex = index;
            this.lightboxOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.classList.remove('overflow-hidden');
        },
        playVideo(id) {
            this.currentVideoId = id;
            this.videoOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeVideo() {
            this.videoOpen = false;
            this.currentVideoId = null;
            document.body.classList.remove('overflow-hidden');
        },
        videoUrl() {
            return this.currentVideoId ? `https://www.youtube.com/embed/${this.currentVideoId}?autoplay=1&rel=0` : '';
        },
        initGallery() {
            this.gallerySlides = this.gallerySlides.map((slide, index) => ({ ...slide, index }));
            this.groupGallery();
            window.addEventListener('resize', () => this.groupGallery());
        },
        groupGallery() {
            const perPage = window.innerWidth >= 1024 ? 5 : window.innerWidth >= 768 ? 4 : 3;
            this.galleryGroups = [];
            for (let i = 0; i < this.gallerySlides.length; i += perPage) {
                this.galleryGroups.push(this.gallerySlides.slice(i, i + perPage));
            }
            if (this.galleryCurrent >= this.galleryGroups.length) {
                this.galleryCurrent = 0;
            }
        },
        startGallery() {
            if (!this.galleryGroups.length) {
                this.initGallery();
            }
            this.stopGallery();
            this.galleryInterval = setInterval(() => this.nextGallery(), 5000);
        },
        stopGallery() {
            if (this.galleryInterval) {
                clearInterval(this.galleryInterval);
                this.galleryInterval = null;
            }
        },
        nextGallery() {
            if (!this.galleryGroups.length) return;
            this.galleryCurrent = (this.galleryCurrent + 1) % this.galleryGroups.length;
        },
        prevGallery() {
            if (!this.galleryGroups.length) return;
            this.galleryCurrent = (this.galleryCurrent - 1 + this.galleryGroups.length) % this.galleryGroups.length;
        },
        next() {
            this.lightboxIndex = (this.lightboxIndex + 1) % this.gallerySlides.length;
        },
        prev() {
            this.lightboxIndex = (this.lightboxIndex - 1 + this.gallerySlides.length) % this.gallerySlides.length;
        }
    }"
    x-init="initGallery(); startGallery()">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[12%] -left-[8%] w-[320px] sm:w-[520px] h-[320px] sm:h-[520px] opacity-20 blur-[100px]"
            style="background: radial-gradient(circle, #00c6ff, transparent 72%);"></div>
        <div class="absolute top-[16%] -right-[8%] w-[320px] sm:w-[520px] h-[320px] sm:h-[520px] opacity-20 blur-[100px]"
            style="background: radial-gradient(circle, #6a5cff, transparent 72%);"></div>
    </div>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-8 pb-12 lg:pt-14 lg:pb-16">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <div>
                <p class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-subtle bg-white/5 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-300">
                    <i class="ri-map-pin-2-line text-cyan-300"></i>
                    Noida Store Location
                </p>

                <h1 class="mt-5 text-3xl sm:text-5xl font-bold leading-[1.1] tracking-tight text-white">Hookah Shop in Noida - Tobac-Go, Sector 76</h1>
                <p class="mt-4 text-sm sm:text-base text-slate-300 leading-relaxed">
                    If you have been searching for a reliable hookah shop in Noida, you already know how hard it is to find one that actually has what you need. Most places either carry limited stock or the staff cannot guide properly.
                </p>
                <p class="mt-3 text-sm sm:text-base text-slate-300 leading-relaxed">
                    At Tobac-Go, we built our store around one simple idea: quality hookahs, fresh flavours, and the right accessories, all in one spot with honest guidance.
                </p>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl border border-white/10 bg-white/[0.03] px-3.5 py-3 text-slate-200 flex items-center gap-2.5">
                        <i class="ri-store-3-line text-cyan-300 text-lg"></i>
                        <span>Amarpali Silicon City, Sector 76</span>
                    </div>
                    <a href="tel:07838449604" class="rounded-xl border border-white/10 bg-white/[0.03] px-3.5 py-3 text-slate-200 flex items-center gap-2.5 hover:border-cyan-300/40 transition">
                        <i class="ri-phone-line text-cyan-300 text-lg"></i>
                        <span>Call: 078384 49604</span>
                    </a>
                    <div class="rounded-xl border border-white/10 bg-white/[0.03] px-3.5 py-3 text-slate-200 flex items-center gap-2.5">
                        <i class="ri-time-line text-cyan-300 text-lg"></i>
                        <span>Mon-Sun, 11:00 AM - 11:00 PM</span>
                    </div>
                    <a href="https://maps.google.com/?q=Shop+No.+38-39,+Street+76+Market,+Amarpali+Silicon+City,+Sector+76,+Noida" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-white/10 bg-white/[0.03] px-3.5 py-3 text-slate-200 flex items-center gap-2.5 hover:border-cyan-300/40 transition">
                        <i class="ri-road-map-line text-cyan-300 text-lg"></i>
                        <span>Get Directions</span>
                    </a>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden border border-subtle shadow-2xl shadow-black/40">
                <img src="{{ asset('images/hookah-shop-in-noida.webp') }}" alt="Hookah Shop in Noida Tobac-Go Sector 76" class="h-[280px] sm:h-[440px] w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4 rounded-xl border border-white/15 bg-black/30 backdrop-blur px-4 py-3">
                    <p class="text-xs uppercase tracking-[0.14em] text-cyan-200">Tobac-Go Noida</p>
                    <p class="text-sm text-white mt-1">Shop No. 38-39, Lower Ground Floor, Street 76 Market, Amarpali Silicon City</p>
                </div>
            </div>
        </div>
    </section>

    

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7">
            <h2 class="text-2xl sm:text-3xl font-semibold text-white">Why People Choose Tobac-Go as Their Hookah Shop in Noida</h2>
            <p class="mt-3 text-sm text-slate-300">We often hear: "I wish I had found you sooner." Here is why customers keep coming back.</p>
            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3.5">
                @foreach($whyChoose as $point)
                    <div class="rounded-xl border border-white/10 bg-white/[0.03] p-3.5 text-sm text-slate-200 flex items-start gap-2.5">
                        <i class="ri-checkbox-circle-fill text-cyan-300 mt-0.5"></i>
                        <span>{{ $point }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6" @mouseenter="stopGallery()" @mouseleave="startGallery()">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl sm:text-3xl font-semibold text-white">Gallery</h2>
                    <p class="mt-2 text-sm text-slate-300">Automatic slider with responsive Tobac-Go gallery images.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="prevGallery()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:border-cyan-300/40">
                        <i class="ri-arrow-left-s-line"></i>
                    </button>
                    <button type="button" @click="nextGallery()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:border-cyan-300/40">
                        <i class="ri-arrow-right-s-line"></i>
                    </button>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-[28px] border border-white/10 bg-black/10">
                <div class="flex transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${galleryCurrent * 100}%)`">
                    <template x-for="(group, groupIndex) in galleryGroups" :key="groupIndex">
                        <div class="min-w-full p-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                                <template x-for="slide in group" :key="slide.src">
                                    <button type="button" @click="openLightbox(slide.index)" class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03]">
                                        <img :src="`/gallery/${slide.src}`" :alt="slide.alt" class="h-44 w-full object-cover transition duration-500 group-hover:scale-105" />
                                        <span class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-center gap-2">
                <template x-for="(group, index) in galleryGroups" :key="index">
                    <button type="button" @click="galleryCurrent = index" :class="galleryCurrent === index ? 'bg-cyan-300' : 'bg-white/20'" class="h-2.5 w-2.5 rounded-full transition"></button>
                </template>
            </div>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <div class="grid lg:grid-cols-2 gap-6">
            <article class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-semibold text-white flex items-center gap-2">
                    <i class="ri-vip-crown-2-line text-cyan-300"></i>
                    Our Hookah Collection
                </h2>
                <p class="mt-3 text-sm text-slate-300">From tabletop to tall floor models, modern to classic styles, we have options for every buyer.</p>
                <ul class="mt-4 space-y-2.5">
                    @foreach($hookahCollection as $item)
                        <li class="text-sm text-slate-200 flex items-start gap-2.5">
                            <i class="ri-arrow-right-up-line text-cyan-300 mt-0.5"></i>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </article>

            <article class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-semibold text-white flex items-center gap-2">
                    <i class="ri-restaurant-2-line text-cyan-300"></i>
                    Hookah Flavours in Noida - Fresh Stock, Always
                </h2>
                <p class="mt-3 text-sm text-slate-300">We keep flavour stock fresh and sealed so every session tastes right.</p>
                <ul class="mt-4 space-y-2.5">
                    @foreach($flavours as $item)
                        <li class="text-sm text-slate-200 flex items-start gap-2.5">
                            <i class="ri-sparkling-2-line text-cyan-300 mt-0.5"></i>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </article>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <article class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7">
            <h2 class="text-xl sm:text-3xl font-semibold text-white flex items-center gap-2">
                <i class="ri-tools-line text-cyan-300"></i>
                Hookah Accessories - Everything in One Place
            </h2>
            <p class="mt-3 text-sm text-slate-300">We also stock hookah chillums, replacement stems, and base jars for part replacement needs.</p>
            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($accessories as $item)
                    <div class="rounded-lg border border-white/10 bg-white/[0.03] px-3 py-2.5 text-sm text-slate-200 flex items-start gap-2.5">
                        <i class="ri-checkbox-circle-line text-cyan-300 mt-0.5"></i>
                        <span>{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-3xl font-semibold text-white">Tobac-Go Videos</h2>
                    <p class="mt-2 text-sm text-slate-300">Play the latest Noida store videos directly on this page.</p>
                </div>
                <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-2 text-xs uppercase tracking-[0.16em] text-slate-400">Tap to play</span>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="video in videos" :key="video.id">
                    <button type="button" @click="playVideo(video.id)" class="group overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] text-left transition hover:border-cyan-300/40">
                        <div class="relative overflow-hidden">
                            <img :src="video.thumbnail" :alt="video.title" class="h-48 w-full object-cover transition duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-black/20"></div>
                            <span class="absolute inset-x-0 bottom-3 mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white shadow-lg">
                                <i class="ri-play-fill text-2xl"></i>
                            </span>
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-white" x-text="video.title"></h3>
                            <p class="mt-2 text-xs text-slate-400">YouTube</p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7">            <h2 class="text-xl sm:text-3xl font-semibold text-white">What Our Customers in Noida Say</h2>
            <div class="mt-5 grid md:grid-cols-3 gap-4">
                <article class="rounded-xl border border-white/10 bg-white/[0.03] p-4">
                    <div class="flex items-center gap-2 text-amber-300 text-sm">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                    <p class="mt-3 text-sm text-slate-200">"Best hookah shop in Noida. Staff helped me choose the right setup in my budget."</p>
                    <p class="mt-3 text-xs text-slate-400">- R. Sharma</p>
                </article>
                <article class="rounded-xl border border-white/10 bg-white/[0.03] p-4">
                    <div class="flex items-center gap-2 text-amber-300 text-sm">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                    <p class="mt-3 text-sm text-slate-200">"Fresh flavours and genuine products. Much better than random online listings."</p>
                    <p class="mt-3 text-xs text-slate-400">- A. Khan</p>
                </article>
                <article class="rounded-xl border border-white/10 bg-white/[0.03] p-4">
                    <div class="flex items-center gap-2 text-amber-300 text-sm">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-half-fill"></i>
                    </div>
                    <p class="mt-3 text-sm text-slate-200">"Everything in one place. Hookah, coals, bowls, hoses - saved me multiple trips."</p>
                    <p class="mt-3 text-xs text-slate-400">- V. Gupta</p>
                </article>
            </div>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-16 sm:pb-20" x-data="{ openFaq: 0 }">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
            <h2 class="text-xl sm:text-3xl font-semibold text-white">Frequently Asked Questions About Our Hookah Shop in Noida</h2>
            <div class="mt-5 space-y-3">
                @foreach($faqs as $index => $faq)
                    <article class="rounded-xl border border-white/10 bg-white/[0.03] overflow-hidden">
                        <button type="button"
                            @click="openFaq = (openFaq === {{ $index }} ? -1 : {{ $index }})"
                            class="w-full px-4 sm:px-5 py-4 text-left flex items-center justify-between gap-3">
                            <h3 class="text-sm sm:text-base font-medium text-white">{{ $faq['q'] }}</h3>
                            <i class="ri-arrow-down-s-line text-slate-300 transition-transform"
                                :class="openFaq === {{ $index }} ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="openFaq === {{ $index }}" x-transition class="px-4 sm:px-5 pb-4">
                            <p class="text-sm text-slate-300 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <div x-show="lightboxOpen"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="closeLightbox()"
        class="fixed inset-0 z-[120] bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 sm:p-8">
        <button type="button" @click="closeLightbox()" class="absolute top-4 right-4 sm:top-6 sm:right-6 h-10 w-10 rounded-full border border-white/20 text-white hover:bg-white/10">
            <i class="ri-close-line text-xl"></i>
        </button>

        <button type="button" @click="prev()" class="absolute left-3 sm:left-6 h-10 w-10 rounded-full border border-white/20 text-white hover:bg-white/10">
            <i class="ri-arrow-left-s-line text-xl"></i>
        </button>
        <button type="button" @click="next()" class="absolute right-3 sm:right-6 h-10 w-10 rounded-full border border-white/20 text-white hover:bg-white/10">
            <i class="ri-arrow-right-s-line text-xl"></i>
        </button>

        <div class="w-full max-w-5xl">
            <img :src="`/gallery/${gallerySlides[lightboxIndex].src}`" :alt="gallerySlides[lightboxIndex].alt" class="w-full max-h-[78vh] object-contain rounded-xl border border-white/20">
            <p class="mt-3 text-center text-sm text-slate-300" x-text="gallerySlides[lightboxIndex].alt"></p>
        </div>
    </div>

    <div x-show="videoOpen"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="closeVideo()"
        class="fixed inset-0 z-[130] bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 sm:p-8">
        <button type="button" @click="closeVideo()" class="absolute top-4 right-4 sm:top-6 sm:right-6 h-10 w-10 rounded-full border border-white/20 text-white hover:bg-white/10">
            <i class="ri-close-line text-xl"></i>
        </button>

        <div class="w-full max-w-5xl">
            <div class="relative overflow-hidden rounded-xl border border-white/20 bg-black">
                <div class="aspect-[16/9]">
                    <iframe
                        class="h-full w-full"
                        :src="videoUrl()"
                        title="YouTube video player"
                        frameborder="0"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <div class="grid lg:grid-cols-12 gap-6">
            <article class="lg:col-span-5 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-semibold text-white">Visit Tobac-Go - Hookah Shop in Sector 76, Noida</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-200">
                    <p><span class="text-slate-400">Store Name:</span> Tobac-Go</p>
                    <p><span class="text-slate-400">Address:</span> Shop No. 38-39, Lower Ground Floor, Street 76 Market, Amarpali Silicon City, Sector 76, Noida, Uttar Pradesh 201316</p>
                    <p><span class="text-slate-400">Phone:</span> <a href="tel:07838449604" class="text-cyan-300 hover:text-cyan-200">078384 49604</a></p>
                    <p><span class="text-slate-400">Store Hours:</span> Mon-Sun, 11:00 AM to 11:00 PM</p>
                </div>
                <p class="mt-4 text-sm text-slate-300">From Sector 78, 137, or Greater Noida West, we are a short drive away and easily reachable from Noida-Greater Noida Expressway.</p>
            </article>

            <article class="lg:col-span-7 rounded-2xl border border-subtle bg-[#0b0d0f] overflow-hidden">
                <iframe
                    title="Tobac-Go Hookah Shop in Noida Map"
                    class="w-full h-[340px] sm:h-[420px]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=Shop+No.+38-39,+Street+76+Market,+Amarpali+Silicon+City,+Sector+76,+Noida&output=embed">
                </iframe>
            </article>
        </div>
    </section>
</div>
