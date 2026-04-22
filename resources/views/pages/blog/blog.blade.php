<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
    <section class="mb-8 sm:mb-10 text-center">
        <p class="text-xs uppercase tracking-[0.34em] text-slate-500">Tobac-Go Journal</p>
        <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-semibold text-white leading-tight">
            Clean reads for setup, care, and product discovery.
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-sm sm:text-base leading-7 text-slate-400">
            A polished collection of short, useful blog posts for customers who want better sessions and smarter buying decisions.
        </p>
    </section>

    @if($category || $tag)
        <div class="mb-8 flex flex-wrap items-center justify-center gap-2">
            @if($category)
                <a href="{{ route('blogs', ['cat' => $category]) }}" wire:navigate class="inline-flex items-center rounded-full border border-cyan-300/25 bg-cyan-400/10 px-4 py-2 text-xs uppercase tracking-[0.18em] text-cyan-200">
                    {{ $this->categories->firstWhere('slug', $category)?->title ?? $category }}
                </a>
            @endif
            @if($tag)
                @php($activeTag = $this->popularTags->firstWhere('slug', $tag))
                <a href="{{ route('blogs', ['tag' => $tag]) }}" wire:navigate class="inline-flex items-center rounded-full border border-white/15 bg-white/[0.04] px-4 py-2 text-xs uppercase tracking-[0.18em] text-slate-200">
                    #{{ $activeTag['label'] ?? $tag }}
                </a>
            @endif
            <button type="button" wire:click="clearFilters" class="inline-flex items-center rounded-full border border-white/12 px-4 py-2 text-xs uppercase tracking-[0.18em] text-slate-400 transition hover:border-white/20 hover:text-white">
                Clear filters
            </button>
        </div>
    @endif

    @if($this->blogs->count())
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
            @foreach($this->blogs as $post)
                <article class="group overflow-hidden rounded-[1.8rem] border border-white/10 bg-[#0b0d0f] transition duration-300 hover:-translate-y-1 hover:border-white/20 hover:shadow-[0_24px_70px_rgba(0,0,0,0.35)]">
                    <a href="{{ route('blog.view', $post->slug) }}" wire:navigate class="block">
                        <div class="relative h-60 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0d0f] via-[#0b0d0f]/10 to-transparent z-10"></div>
                            <div class="absolute inset-0 opacity-0 transition duration-300 group-hover:opacity-100 z-10" style="background: radial-gradient(circle at top, rgba(34,211,238,0.14), transparent 48%);"></div>
                            <img
                                src="{{ $post->featured_image ? asset('storage/' . ltrim($post->featured_image, '/')) : asset('images/hero.png') }}"
                                alt="{{ $post->title }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            >

                            <div class="absolute left-4 right-4 top-4 z-20 flex items-center justify-between gap-3">
                                <span class="inline-flex items-center rounded-full border border-white/15 bg-black/35 px-3 py-1 text-[10px] uppercase tracking-[0.22em] text-white backdrop-blur">
                                    {{ $post->category?->title ?? 'General' }}
                                </span>
                                <span class="inline-flex items-center rounded-full border border-white/10 bg-black/25 px-3 py-1 text-[10px] uppercase tracking-[0.18em] text-slate-200 backdrop-blur">
                                    {{ $this->readingTime($post->content) }}
                                </span>
                            </div>
                        </div>
                    </a>

                    <div class="p-5 sm:p-6">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">
                            {{ optional($post->created_at)->format('d M Y') }}
                        </p>

                        <h2 class="mt-3 text-xl font-semibold text-white leading-snug">
                            <a href="{{ route('blog.view', $post->slug) }}" wire:navigate class="transition hover:text-cyan-100">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="mt-3 text-sm leading-7 text-slate-400">
                            {{ \Illuminate\Support\Str::limit(strip_tags((string) $post->content), 132) }}
                        </p>

                        <div class="mt-5 flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-500">
                                {{ $post->author?->name ?? 'Tobac-Go Team' }}
                            </span>
                            <a href="{{ route('blog.view', $post->slug) }}" wire:navigate class="inline-flex items-center gap-2 text-sm text-cyan-300 transition hover:text-cyan-200">
                                Explore <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        @if($this->blogs->lastPage() > 1)
            <nav class="mt-10 flex flex-col items-center gap-4">
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">
                    Page {{ $this->blogs->currentPage() }} of {{ $this->blogs->lastPage() }}
                </p>

                <div class="inline-flex flex-wrap items-center justify-center gap-2 rounded-full border border-white/10 bg-[#0b0d0f] px-3 py-3 shadow-[0_20px_60px_rgba(0,0,0,0.28)]">
                    @if($this->blogs->onFirstPage())
                        <span class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/8 px-4 text-sm text-slate-600">
                            <i class="ri-arrow-left-s-line"></i>
                        </span>
                    @else
                        <a href="{{ route('blogs', array_filter(['page' => $this->blogs->currentPage() - 1, 'cat' => $category ?: null, 'tag' => $tag ?: null])) }}" wire:navigate class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/10 px-4 text-sm text-slate-200 transition hover:border-white/20 hover:bg-white/[0.05]">
                            <i class="ri-arrow-left-s-line"></i>
                        </a>
                    @endif

                    @foreach($this->paginationItems() as $item)
                        @if($item === 'ellipsis')
                            <span class="inline-flex h-11 min-w-11 items-center justify-center text-sm text-slate-500">...</span>
                        @elseif($item === $this->blogs->currentPage())
                            <span class="inline-flex h-11 min-w-11 items-center justify-center rounded-full bg-white text-sm font-semibold text-black">
                                {{ $item }}
                            </span>
                        @else
                            <a href="{{ route('blogs', array_filter(['page' => $item, 'cat' => $category ?: null, 'tag' => $tag ?: null])) }}" wire:navigate class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/10 text-sm text-slate-200 transition hover:border-cyan-300/35 hover:bg-cyan-400/10 hover:text-cyan-100">
                                {{ $item }}
                            </a>
                        @endif
                    @endforeach

                    @if($this->blogs->hasMorePages())
                        <a href="{{ route('blogs', array_filter(['page' => $this->blogs->currentPage() + 1, 'cat' => $category ?: null, 'tag' => $tag ?: null])) }}" wire:navigate class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/10 px-4 text-sm text-slate-200 transition hover:border-white/20 hover:bg-white/[0.05]">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    @else
                        <span class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/8 px-4 text-sm text-slate-600">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    @endif
                </div>
            </nav>
        @endif
    @else
        <div class="rounded-[2rem] border border-dashed border-white/15 bg-white/[0.02] px-6 py-16 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.03] text-slate-300">
                <i class="ri-article-line text-2xl"></i>
            </div>
            <h2 class="mt-4 text-xl font-semibold text-white">No blog posts found</h2>
            <p class="mt-2 text-sm text-slate-400">Try another category or remove the current tag filter.</p>
            <button type="button" wire:click="clearFilters" class="mt-5 inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-black transition hover:opacity-90">
                Show all posts
            </button>
        </div>
    @endif
</div>
