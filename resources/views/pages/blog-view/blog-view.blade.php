@section('meta_title', $this->blog->meta_title ?? ($this->blog->title . ' | Tobac-Go Journal'))
@section('meta_description', $this->blog->meta_description ?? \Illuminate\Support\Str::limit(strip_tags((string) $this->blog->content), 155))
@section('meta_keywords', $this->blog->meta_keywords ?? ($this->blog->tags ? implode(', ', (array) $this->blog->tags) : 'hookah blog, shisha news, Tobac-Go journal'))

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">

    <section class="relative overflow-hidden rounded-[2.2rem] border border-white/10 bg-[radial-gradient(circle_at_12%_16%,rgba(45,212,191,0.18),transparent_28%),radial-gradient(circle_at_88%_18%,rgba(56,189,248,0.16),transparent_32%),#0b0d0f]">
        <div class="absolute inset-0 opacity-70 pointer-events-none" style="background: linear-gradient(145deg, rgba(255,255,255,0.03), transparent 45%, rgba(34,211,238,0.08));"></div>
        <div class="relative grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-0">
            <div class="p-6 sm:p-8 lg:p-10 xl:p-12">
                <div class="flex flex-wrap items-center gap-2 text-[11px] uppercase tracking-[0.22em] text-slate-400">
                    <a href="{{ route('blogs') }}" wire:navigate class="transition hover:text-white">Blog</a>
                    <span>/</span>
                    @if($this->blog->category)
                        <a href="{{ route('blogs', ['cat' => $this->blog->category->slug]) }}" wire:navigate class="text-cyan-200 transition hover:text-cyan-100">
                            {{ $this->blog->category->title }}
                        </a>
                    @endif
                </div>

                <h1 class="mt-5 max-w-4xl text-3xl sm:text-4xl xl:text-5xl font-semibold leading-tight text-white">
                    {{ $this->blog->title }}
                </h1>

                <div class="mt-6 flex flex-wrap items-center gap-2.5 text-xs sm:text-sm text-slate-300">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-3 py-1.5">
                        <i class="ri-user-3-line"></i>{{ $this->blog->author?->name ?? 'Tobac-Go Team' }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-3 py-1.5">
                        <i class="ri-calendar-line"></i>{{ optional($this->blog->created_at)->format('d M Y') }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-3 py-1.5">
                        <i class="ri-time-line"></i>{{ $this->readingTime($this->blog->content) }}
                    </span>
                </div>
            </div>

            <div class="relative min-h-[260px] lg:min-h-full border-t lg:border-t-0 lg:border-l border-white/10">
                <img
                    src="{{ $this->blog->featured_image ? asset('storage/' . ltrim($this->blog->featured_image, '/')) : asset('images/hero.png') }}"
                    alt="{{ $this->blog->title }}"
                    class="h-full w-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-[#0b0d0f] via-transparent to-transparent lg:bg-gradient-to-l lg:from-transparent lg:to-[#0b0d0f]/35"></div>
            </div>
        </div>
    </section>

    <section class="mt-8 grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-6 xl:gap-8 items-start">
        <article class="rounded-[2rem] border border-white/10 bg-[#0b0d0f] p-6 sm:p-8 lg:p-10">
            @if(!empty($this->blog->tag_list))
                <div class="mb-7 flex flex-wrap gap-2">
                    @foreach($this->blog->tagItems() as $item)
                        <a href="{{ route('blogs', ['tag' => $item['slug']]) }}" wire:navigate class="rounded-full border border-white/12 bg-white/[0.03] px-3.5 py-1.5 text-xs text-slate-300 transition hover:border-cyan-300/35 hover:bg-cyan-400/10 hover:text-cyan-100">
                            #{{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="tiny-content prose prose-invert max-w-none prose-headings:text-white prose-p:text-slate-300 prose-p:leading-8 prose-strong:text-white prose-a:text-cyan-300 prose-li:text-slate-300 prose-blockquote:border-cyan-400/30 prose-blockquote:text-slate-300 prose-img:rounded-[1.5rem]">
                {!! $this->blog->content !!}
            </div>
        </article>

        <aside class="space-y-5 xl:sticky xl:top-24">
            <div class="rounded-[1.8rem] border border-white/10 bg-[#0b0d0f] p-5">
                <p class="text-xs uppercase tracking-[0.26em] text-slate-500">Continue Reading</p>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('blogs') }}" wire:navigate class="flex items-center justify-between rounded-2xl border border-white/10 px-4 py-3 text-sm text-slate-200 transition hover:border-white/20 hover:bg-white/[0.04]">
                        <span>All Articles</span>
                        <i class="ri-arrow-right-line"></i>
                    </a>
                    @if($this->blog->category)
                        <a href="{{ route('blogs', ['cat' => $this->blog->category->slug]) }}" wire:navigate class="flex items-center justify-between rounded-2xl border border-cyan-300/20 bg-cyan-400/10 px-4 py-3 text-sm text-cyan-200 transition hover:border-cyan-300/35">
                            <span>{{ $this->blog->category->title }}</span>
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    @endif
                </div>
            </div>

            @if($this->recentPosts->isNotEmpty())
                <div class="rounded-[1.8rem] border border-white/10 bg-[#0b0d0f] p-5">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs uppercase tracking-[0.26em] text-slate-500">Latest Posts</p>
                        <a href="{{ route('blogs') }}" wire:navigate class="text-xs text-cyan-300 hover:text-cyan-200 transition">View all</a>
                    </div>

                    @php($featuredRecent = $this->recentPosts->first())
                    @if($featuredRecent)
                        <a href="{{ route('blog.view', $featuredRecent->slug) }}" wire:navigate class="group block mt-4 rounded-2xl border border-white/10 overflow-hidden transition hover:border-white/20">
                            <div class="relative h-44">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0b0d0f] via-[#0b0d0f]/20 to-transparent z-10"></div>
                                <img
                                    src="{{ $featuredRecent->featured_image ? asset('storage/' . ltrim($featuredRecent->featured_image, '/')) : asset('images/hero.png') }}"
                                    alt="{{ $featuredRecent->title }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                >
                                <span class="absolute top-3 left-3 z-20 inline-flex items-center rounded-full border border-white/10 bg-black/35 px-2.5 py-1 text-[10px] uppercase tracking-[0.18em] text-slate-200">
                                    {{ $featuredRecent->category?->title ?? 'General' }}
                                </span>
                            </div>
                            <div class="p-4">
                                <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500">{{ optional($featuredRecent->created_at)->format('d M Y') }}</p>
                                <h3 class="mt-2 text-sm font-medium text-white leading-6 group-hover:text-cyan-100 transition line-clamp-2">
                                    {{ $featuredRecent->title }}
                                </h3>
                            </div>
                        </a>
                    @endif

                    <div class="mt-4 space-y-2.5">
                        @foreach($this->recentPosts->skip(1) as $post)
                            <a href="{{ route('blog.view', $post->slug) }}" wire:navigate class="group flex items-center gap-3 rounded-2xl border border-white/10 p-2.5 transition hover:border-white/20 hover:bg-white/[0.04]">
                                <img
                                    src="{{ $post->featured_image ? asset('storage/' . ltrim($post->featured_image, '/')) : asset('images/hero.png') }}"
                                    alt="{{ $post->title }}"
                                    class="h-16 w-16 rounded-xl object-cover flex-shrink-0"
                                >
                                <div class="min-w-0">
                                    <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500">{{ optional($post->created_at)->format('d M Y') }}</p>
                                    <h3 class="mt-1 text-sm font-medium text-slate-200 group-hover:text-cyan-100 transition line-clamp-2">
                                        {{ $post->title }}
                                    </h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($this->relatedPosts->isNotEmpty())
                <div class="rounded-[1.8rem] border border-white/10 bg-[#0b0d0f] p-5">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs uppercase tracking-[0.26em] text-slate-500">Related Posts</p>
                        <span class="text-[11px] text-slate-500">{{ $this->relatedPosts->count() }} picks</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @foreach($this->relatedPosts as $index => $post)
                            <a href="{{ route('blog.view', $post->slug) }}" wire:navigate class="group grid grid-cols-[30px_1fr] items-start gap-3 rounded-2xl border border-white/10 p-3 transition hover:border-white/20 hover:bg-white/[0.04]">
                                <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-full border border-cyan-300/25 bg-cyan-400/10 text-xs font-semibold text-cyan-200">
                                    {{ $index + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500">
                                        {{ $post->category?->title ?? 'General' }} / {{ optional($post->created_at)->format('d M Y') }}
                                    </p>
                                    <h3 class="mt-1.5 text-sm font-medium text-slate-100 leading-6 transition group-hover:text-cyan-100 line-clamp-2">
                                        {{ $post->title }}
                                    </h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </section>
</div>

<style>
    .tiny-content iframe,
    .tiny-content video,
    .tiny-content table {
        width: 100%;
        max-width: 100%;
    }

    .tiny-content iframe {
        min-height: 320px;
        border-radius: 1rem;
    }

    .tiny-content table {
        display: block;
        overflow-x: auto;
        border-collapse: collapse;
    }

    .tiny-content table td,
    .tiny-content table th {
        border: 1px solid rgba(255, 255, 255, 0.12);
        padding: 0.75rem;
    }
 </style>
