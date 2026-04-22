<div>
    <!-- Hero / Header Section -->
    <section class="relative overflow-hidden pt-10 pb-10 lg:pt-32 lg:pb-16">
        <!-- Ambient Glow -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[10%] left-[10%] w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] opacity-20 blur-[100px] sm:blur-[120px]"
                style="background: radial-gradient(circle, #00c6ff, transparent 70%);">
            </div>
            <div class="absolute bottom-[10%] right-[10%] w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] opacity-20 blur-[100px] sm:blur-[120px]"
                style="background: radial-gradient(circle, #6a5cff, transparent 70%);">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-subtle bg-white/5 text-xs text-muted mb-6 backdrop-blur">
                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-cyan-400 to-pink-500 animate-pulse"></span>
                Explore Our Collection
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-5xl font-bold leading-[1.1] mb-6 tracking-tight">
                Shop by <br class="sm:hidden">
                <span class="text-transparent text-bold bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400">
                    Category
                </span>
            </h1>

            <p class="text-muted text-sm sm:text-base max-w-2xl mx-auto">
                Discover our premium range of hookahs, accessories, and essentials. Whether you're looking for luxury, budget, or everyday pieces, we have everything you need in one place.
            </p>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-24">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($categories as $category)
                <a href="{{ route('products.category', $category->slug) }}" wire:navigate class="group relative rounded-2xl border border-subtle bg-[#0b0d0f] p-4 sm:p-5 text-center transition duration-300 hover:-translate-y-1 hover:border-white/20 hover:shadow-2xl hover:shadow-cyan-900/20 block overflow-hidden">
                    
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none" style="background: radial-gradient(circle at top, rgba(0,198,255,0.08), transparent 70%);"></div>

                    <div class="relative mx-auto mb-5 flex h-28 sm:h-32 items-center justify-center overflow-hidden rounded-xl border border-white/10 bg-white/[0.03]">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500" style="background: radial-gradient(circle at center, rgba(0,114,255,0.15), transparent 60%);"></div>
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->title }}" class="relative h-20 sm:h-24 object-contain transition duration-500 group-hover:scale-110 drop-shadow-[0_10px_20px_rgba(0,0,0,0.6)]">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/5 border border-white/10 text-indigo-300">
                                <i class="ri-image-line text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="text-white text-sm sm:text-base font-semibold mb-1">{{ $category->title }}</h3>
                    <p class="text-muted text-[11px] sm:text-xs line-clamp-2 min-h-[32px]">{{ $category->description ?? 'Explore premium ' . strtolower($category->title) }}</p>

                    <div class="mt-4 flex items-center justify-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-indigo-300/70 group-hover:text-indigo-300 transition">
                        View Products <i class="ri-arrow-right-up-line"></i>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-white/15 bg-white/[0.02] px-6 py-16 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white/5 text-white/50">
                        <i class="ri-ghost-line text-3xl"></i>
                    </div>
                    <h3 class="text-white text-lg font-medium mb-2">No Categories Found</h3>
                    <p class="text-muted text-sm max-w-md mx-auto">We are currently updating our catalog. Please check back later for our premium collection.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Support CTA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 pb-24">
        <div class="relative overflow-hidden rounded-3xl border border-subtle bg-[#0b0d0f] p-8 sm:p-12 text-center shadow-2xl shadow-black/40">
            <div class="absolute left-1/2 top-0 h-64 w-64 -translate-x-1/2 rounded-full bg-orange-500/10 blur-[90px] pointer-events-none"></div>
            <div class="relative max-w-2xl mx-auto">
                <p class="text-xs uppercase tracking-[0.28em] text-muted mb-3 font-semibold">Expert Advice</p>
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Need Help Choosing?</h2>
                <p class="text-muted text-sm sm:text-base leading-relaxed mb-8">
                    Not sure which category fits your needs? Reach out to our experts. We'll help you find the perfect setup based on your preferences and budget.
                </p>
                <a href="https://wa.me/917838449604" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-full px-8 py-3.5 text-sm font-bold text-black transition-transform hover:scale-105 shadow-[0_0_20px_rgba(0,198,255,0.15)]" style="background: var(--gradient-acrylic);">
                    <i class="ri-whatsapp-line text-xl"></i>
                    Chat on WhatsApp
                </a>
            </div>
        </div>
    </section>
</div>