<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">SEO Management</h1>
            <p class="text-sm text-slate-500 mt-1">Manage search engine optimization titles, meta descriptions, and keywords for public pages.</p>
        </div>

        <button
            @click="$dispatch('open-modal'); $wire.resetForm()"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-500 transition"
        >
            <i class="ri-add-line text-base"></i>
            Add SEO Entry
        </button>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
        <div class="relative w-full sm:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="ri-search-line"></i>
            </span>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by name, slug, or title..."
                class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition"
            >
        </div>

        <div class="w-full sm:w-44">
            <select
                wire:model.live="perPage"
                class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition"
            >
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>
    </div>

    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Page Name</th>
                    <th class="px-6 py-4 text-left">URL Slug</th>
                    <th class="px-6 py-4 text-left">Meta Title</th>
                    <th class="px-6 py-4 text-left">Meta Description</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($seoPages as $seo)
                    <tr wire:key="seo-{{ $seo->id }}" class="hover:bg-slate-50 transition">
                        <td class="px-6 py-5">
                            <p class="font-semibold text-slate-900 tracking-wide">{{ $seo->name }}</p>
                            <p class="text-xs text-slate-400 mt-1">#{{ $seo->id }}</p>
                        </td>

                        <td class="px-6 py-5 text-slate-600 font-mono text-xs">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-700">
                                {{ $seo->page_slug }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-slate-700 font-medium max-w-xs truncate">
                            {{ $seo->meta_title }}
                        </td>

                        <td class="px-6 py-5 text-slate-500 max-w-md truncate">
                            {{ $seo->meta_description }}
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    @click="$dispatch('open-modal'); $wire.openEditModal({{ $seo->id }})"
                                    class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-100 transition"
                                >
                                    Edit
                                </button>

                                <button
                                    @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $seo->id }})"
                                    class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-semibold hover:bg-rose-100 transition"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">No SEO configurations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sm:hidden space-y-4">
        @forelse($seoPages as $seo)
            <div wire:key="mobile-seo-{{ $seo->id }}" class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900 tracking-wide">{{ $seo->name }}</p>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $seo->page_slug }}</p>
                    </div>
                </div>

                <div class="text-xs space-y-1">
                    <p class="text-slate-700"><span class="font-medium text-slate-500">Title:</span> {{ $seo->meta_title }}</p>
                    <p class="text-slate-500 line-clamp-2"><span class="font-medium text-slate-400">Desc:</span> {{ $seo->meta_description }}</p>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button
                        @click="$dispatch('open-modal'); $wire.openEditModal({{ $seo->id }})"
                        class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs font-medium"
                    >
                        Edit
                    </button>

                    <button
                        @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $seo->id }})"
                        class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium"
                    >
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">
                No SEO configurations found.
            </div>
        @endforelse
    </div>

    <div class="flex justify-center">
        {{ $seoPages->onEachSide(1)->links() }}
    </div>

    {{-- SEO Save/Edit Modal --}}
    <div
        x-data="{ modalOpen: false }"
        x-on:open-modal.window="modalOpen = true"
        x-on:close-modal.window="modalOpen = false"
        x-cloak
    >
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
                <div @click="modalOpen=false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

                <div
                    x-show="modalOpen"
                    x-transition
                    x-trap.inert.noscroll="modalOpen"
                    class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl max-h-[90vh] flex flex-col overflow-hidden"
                >
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $selectedId ? 'Edit SEO Entry' : 'Add SEO Entry' }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">Customize metadata settings for this page.</p>
                        </div>

                        <button @click="modalOpen=false"
                            class="h-9 w-9 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                            <i class="ri-close-line text-lg"></i>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-5 text-sm">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-medium text-gray-600">Page Name</label>
                                <input
                                    type="text"
                                    wire:model.live="name"
                                    placeholder="e.g. Home Page, Contact Us"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                >
                                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-600">URL Slug</label>
                                <input
                                    type="text"
                                    wire:model.live="page_slug"
                                    placeholder="e.g. / or shop or return-refund"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                >
                                @error('page_slug')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                <p class="text-[11px] text-gray-400 mt-1">Use '/' for Home Page. Use clean slugs (e.g. 'shop', 'return-refund') for other pages.</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-600">Meta Title</label>
                                <input
                                    type="text"
                                    wire:model.live="meta_title"
                                    placeholder="SEO Title (Optimal length: 50-60 characters)"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                >
                                @error('meta_title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-600">Meta Description</label>
                                <textarea
                                    wire:model.live="meta_description"
                                    rows="3"
                                    placeholder="SEO Description (Optimal length: 150-160 characters)"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                ></textarea>
                                @error('meta_description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-600">Meta Keywords</label>
                                <textarea
                                    wire:model.live="meta_keywords"
                                    rows="2"
                                    placeholder="Comma-separated keywords (e.g. hookah online, premium shisha, buy hookah india)"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                ></textarea>
                                @error('meta_keywords')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-600">Content</label>
                                <div
                                    wire:ignore
                                    x-data="{
                                        editor: null,
                                        init() {
                                            const textarea = this.$refs.textarea;
                                            const existing = window.tinymce?.get(textarea.id);
                                            if (existing) existing.remove();

                                            window.tinymce.init({
                                                target: textarea,
                                                height: 320,
                                                menubar: false,
                                                plugins: 'lists link image paste help wordcount',
                                                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
                                                setup: (editor) => {
                                                    this.editor = editor;
                                                    editor.on('init', () => editor.setContent(textarea.value || ''));
                                                    editor.on('Change KeyUp Undo Redo', () => this.$wire.set('content', editor.getContent()));
                                                },
                                            });
                                        },
                                        destroy() {
                                            if (this.editor) this.editor.remove();
                                        },
                                    }"
                                >
                                    <textarea
                                        id="seo-content-add"
                                        x-ref="textarea"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900"
                                        placeholder="Add custom content (for specific landing pages)...">{{ $content }}</textarea>
                                </div>
                                @error('content')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <button @click="modalOpen=false"
                            class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                            Cancel
                        </button>

                        <button
                            wire:click="save"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition disabled:opacity-60"
                        >
                            <span wire:loading.remove wire:target="save">Save</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- SEO Delete Modal --}}
    <div
        x-data="{ deleteOpen: false }"
        x-on:open-delete-modal.window="deleteOpen = true"
        x-on:close-delete-modal.window="deleteOpen = false"
        x-cloak
    >
        <template x-teleport="body">
            <div x-show="deleteOpen" class="fixed inset-0 z-[99] flex items-center justify-center px-4">
                <div @click="deleteOpen=false" class="absolute inset-0 bg-black/40"></div>

                <div
                    x-show="deleteOpen"
                    x-transition
                    x-trap.inert.noscroll="deleteOpen"
                    class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-xl"
                >
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Delete SEO Content</h3>
                    <p class="mb-6 text-slate-700">
                        Are you sure you want to delete this SEO page content? This action cannot be undone.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button
                            @click="deleteOpen=false"
                            class="rounded-md border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        >
                            Cancel
                        </button>

                        <button
                            wire:click="delete"
                            class="inline-flex items-center gap-1 rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-500"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>