<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Testimonial Management</h1>
            <p class="text-sm text-slate-500 mt-1">Manage customer testimonials and their display order.</p>
        </div>

        <button
            @click="$dispatch('open-modal'); $wire.resetForm()"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-500 transition"
        >
            <i class="ri-add-line text-base"></i>
            Add Testimonial
        </button>
    </div>

    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search testimonials..."
            class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition text-gray-900"
        >
    </div>

    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-4 w-10"></th>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Review</th>
                    <th class="px-6 py-4 text-left">Stars</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>

            <tbody wire:sort="handleTestimonialSort" class="divide-y divide-slate-100">
                @forelse($testimonials as $t)
                    <tr
                        wire:key="testimonial-{{ $t->id }}"
                        wire:sort:item="{{ $t->id }}"
                        class="hover:bg-slate-50 transition"
                    >
                        <td class="px-4 py-5 text-slate-400">
                            <span wire:sort:handle class="cursor-move hover:text-slate-600">
                                <i class="ri-draggable text-base"></i>
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-9 w-9 flex items-center justify-center rounded-md bg-amber-50 text-amber-600">
                                    <i class="ri-user-line text-base"></i>
                                </div>

                                <div>
                                    <p class="font-medium text-slate-900">{{ $t->name }}</p>
                                    @if($t->city)
                                        <p class="text-xs text-slate-400 mt-1">{{ $t->city }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <p class="text-slate-600 text-sm line-clamp-2">{{ $t->review }}</p>
                        </td>

                        <td class="px-6 py-5">
                            <span class="text-yellow-500 font-bold">{{ $t->stars }} ★</span>
                        </td>

                        <td class="px-6 py-5">
                            @if($t->is_active)
                                <span class="text-emerald-600 text-xs font-medium">Active</span>
                            @else
                                <span class="text-rose-600 text-xs font-medium">Inactive</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div wire:sort:ignore class="flex justify-end gap-2">
                                <button
                                    @click="$dispatch('open-modal'); $wire.openEditModal({{ $t->id }})"
                                    class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs"
                                >
                                    Edit
                                </button>

                                <button
                                    @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $t->id }})"
                                    class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">No testimonials found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sm:hidden space-y-4">
        @forelse($testimonials as $t)
            <div wire:key="mobile-testimonial-{{ $t->id }}" class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">
                <div class="flex items-start gap-3">
                    <div class="h-9 w-9 flex items-center justify-center rounded-md overflow-hidden bg-amber-50 text-amber-600">
                        <i class="ri-user-line text-base"></i>
                    </div>

                    <div class="flex-1">
                        <p class="font-medium text-slate-900">{{ $t->name }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ \Illuminate\Support\Str::limit($t->review, 120) }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    @if($t->is_active)
                        <span class="text-xs font-medium text-emerald-600">Active</span>
                    @else
                        <span class="text-xs font-medium text-rose-600">Inactive</span>
                    @endif

                    <div class="flex gap-2">
                        <button
                            @click="$dispatch('open-modal'); $wire.openEditModal({{ $t->id }})"
                            class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs font-medium"
                        >
                            Edit
                        </button>

                        <button
                            @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $t->id }})"
                            class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">No testimonials found.</div>
        @endforelse
    </div>

    @include('livewire.testimonial.testimonial-modal')
    @include('livewire.testimonial.delete')
</div>