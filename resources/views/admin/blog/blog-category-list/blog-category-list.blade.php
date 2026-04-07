<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Blog Category Management</h1>
            <p class="text-sm text-slate-500 mt-1">Create and manage blog categories.</p>
        </div>

        <button
            @click="$wire.resetForm(); $dispatch('open-modal')"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-500 transition"
        >
            <i class="ri-add-line text-base"></i>
            Add Category
        </button>
    </div>

    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search blog categories..."
            class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition text-gray-900"
        >
    </div>

    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Title</th>
                    <th class="px-6 py-4 text-left">Slug</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    <tr wire:key="blog-category-{{ $category->id }}" class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $category->title }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            @if($category->is_active)
                                <span class="text-emerald-600 text-xs font-medium">Active</span>
                            @else
                                <span class="text-rose-600 text-xs font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="$wire.openEditModal({{ $category->id }}); $dispatch('open-modal')" class="px-3 py-1.5 rounded-md bg-blue-50 text-blue-600 text-xs">Edit</button>
                                <button @click="$wire.confirmDelete({{ $category->id }}); $dispatch('open-delete-modal')" class="px-3 py-1.5 rounded-md bg-rose-50 text-rose-600 text-xs">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-400">No blog categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sm:hidden space-y-4">
        @forelse($categories as $category)
            <div wire:key="mobile-blog-category-{{ $category->id }}" class="bg-white border border-slate-200 rounded-md p-4 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-medium text-slate-900">{{ $category->title }}</p>
                        <p class="text-xs text-slate-400 font-mono mt-2 break-all">{{ $category->slug }}</p>
                        @if($category->is_active)
                            <span class="text-xs font-medium text-emerald-600">Active</span>
                        @else
                            <span class="text-xs font-medium text-rose-600">Inactive</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button @click="$wire.openEditModal({{ $category->id }}); $dispatch('open-modal')" class="px-3 py-1 rounded-md bg-blue-50 text-blue-600 text-xs">Edit</button>
                        <button @click="$wire.confirmDelete({{ $category->id }}); $dispatch('open-delete-modal')" class="px-3 py-1 rounded-md bg-rose-50 text-rose-600 text-xs">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">No blog categories found.</div>
        @endforelse
    </div>

    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/20">
        {{ $categories->links() }}
    </div>

    @include('livewire.blog-category.category-modal')
    @include('livewire.blog-category.delete')
</div>
