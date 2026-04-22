@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="w-full">
            <div class="flex items-center justify-between gap-3 sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm font-medium text-slate-600 cursor-default">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-[#0b0d0f] px-4 py-2.5 text-sm font-medium text-slate-200 transition duration-150 hover:border-white/20 hover:bg-white/[0.05] hover:text-white">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>

                <span class="inline-flex items-center justify-center rounded-full border border-white/10 bg-[#0b0d0f] px-4 py-2.5 text-xs font-medium uppercase tracking-[0.22em] text-slate-400">
                    {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-[#0b0d0f] px-4 py-2.5 text-sm font-medium text-slate-200 transition duration-150 hover:border-white/20 hover:bg-white/[0.05] hover:text-white">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm font-medium text-slate-600 cursor-default">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex sm:items-center sm:justify-between sm:gap-6">
                <div>
                    <p class="text-sm text-slate-400 leading-5">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-semibold text-white">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-semibold text-white">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-semibold text-white">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>

                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-[#0b0d0f] px-3 py-3 shadow-[0_20px_60px_rgba(0,0,0,0.28)] rtl:flex-row-reverse">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/8 px-4 text-sm text-slate-600 cursor-default" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/10 px-4 text-sm font-medium text-slate-200 transition duration-150 hover:border-white/20 hover:bg-white/[0.05] hover:text-white" aria-label="{{ __('pagination.previous') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="inline-flex h-11 min-w-11 items-center justify-center text-sm font-medium text-slate-500 cursor-default">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="inline-flex h-11 min-w-11 items-center justify-center rounded-full bg-white px-4 text-sm font-semibold text-black cursor-default">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/10 px-4 text-sm font-medium text-slate-200 transition duration-150 hover:border-cyan-300/35 hover:bg-cyan-400/10 hover:text-cyan-100" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/10 px-4 text-sm font-medium text-slate-200 transition duration-150 hover:border-white/20 hover:bg-white/[0.05] hover:text-white" aria-label="{{ __('pagination.next') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/8 px-4 text-sm text-slate-600 cursor-default" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
