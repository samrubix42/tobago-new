<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Blog Management</h1>
            <p class="text-sm text-slate-500 mt-1">Create, edit and manage blog posts.</p>
        </div>

        <button
            @click="$dispatch('open-modal'); $wire.resetForm()"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-500 transition"
        >
            <i class="ri-add-line text-base"></i>
            Add Blog
        </button>
    </div>

    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search blogs..."
            class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition text-gray-900"
        >
    </div>

    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Title</th>
                    <th class="px-6 py-4 text-left">Category</th>
                    <th class="px-6 py-4 text-left">Author</th>
                    <th class="px-6 py-4 text-left">Published</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($blogs as $blog)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $blog->title }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $blog->slug }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $blog->category?->name }}</td>
                        <td class="px-6 py-4">{{ $blog->author?->name ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $blog->is_published ? 'Yes' : 'No' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="$dispatch('open-modal'); $wire.openEditModal({{ $blog->id }})" class="px-3 py-1.5 rounded-md bg-blue-50 text-blue-600 text-xs">Edit</button>
                                <button @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $blog->id }})" class="px-3 py-1.5 rounded-md bg-rose-50 text-rose-600 text-xs">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">No blogs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sm:hidden space-y-4">
        @forelse($blogs as $blog)
            <div class="bg-white border border-slate-200 rounded-md p-4 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-medium text-slate-900">{{ $blog->title }}</p>
                        <p class="text-xs text-slate-400">{{ $blog->category?->name }}</p>
                    </div>

                    <div class="flex gap-2">
                        <button @click="$dispatch('open-modal'); $wire.openEditModal({{ $blog->id }})" class="px-3 py-1 rounded-md bg-blue-50 text-blue-600 text-xs">Edit</button>
                        <button @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $blog->id }})" class="px-3 py-1 rounded-md bg-rose-50 text-rose-600 text-xs">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">No blogs yet.</div>
        @endforelse
    </div>

    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/20">
        {{ $blogs->links() }}
    </div>

    @include('livewire.blog.blog-modal')
    @include('livewire.blog.delete')
</div>
