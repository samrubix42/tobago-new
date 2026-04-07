<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Coupon Management</h1>
            <p class="text-sm text-slate-500 mt-1">Create and manage discount coupons.</p>
        </div>

        <button
            @click="$dispatch('open-modal'); $wire.resetForm()"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-500 transition"
        >
            <i class="ri-add-line text-base"></i>
            Add Coupon
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
                placeholder="Search coupons..."
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
                    <th class="px-6 py-4 text-left">Code</th>
                    <th class="px-6 py-4 text-left">Type</th>
                    <th class="px-6 py-4 text-left">Value</th>
                    <th class="px-6 py-4 text-left">Min Amount</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($coupons as $coupon)
                    <tr wire:key="coupon-{{ $coupon->id }}" class="hover:bg-slate-50 transition">
                        <td class="px-6 py-5">
                            <p class="font-semibold text-slate-900 tracking-wide">{{ $coupon->code }}</p>
                            <p class="text-xs text-slate-400 mt-1">#{{ $coupon->id }}</p>
                        </td>

                        <td class="px-6 py-5 text-slate-600">
                            <span class="inline-flex items-center gap-2">
                                <i class="{{ $coupon->type === 'percentage' ? 'ri-percent-line' : 'ri-money-rupee-circle-line' }} text-slate-400"></i>
                                {{ ucfirst($coupon->type) }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-slate-700 font-medium">
                            @if($coupon->type === 'percentage')
                                {{ rtrim(rtrim(number_format((float) $coupon->value, 2, '.', ''), '0'), '.') }}%
                            @else
                                ₹{{ number_format((float) $coupon->value, 2) }}
                            @endif
                        </td>

                        <td class="px-6 py-5 text-slate-700">
                            ₹{{ number_format((float) $coupon->min_amount, 2) }}
                        </td>

                        <td class="px-6 py-5">
                            @if($coupon->is_active)
                                <span class="text-emerald-600 text-xs font-medium">Active</span>
                            @else
                                <span class="text-rose-600 text-xs font-medium">Inactive</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    @click="$dispatch('open-modal'); $wire.openEditModal({{ $coupon->id }})"
                                    class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs"
                                >
                                    Edit
                                </button>

                                <button
                                    @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $coupon->id }})"
                                    class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">No coupons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sm:hidden space-y-4">
        @forelse($coupons as $coupon)
            <div wire:key="mobile-coupon-{{ $coupon->id }}" class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900 tracking-wide">{{ $coupon->code }}</p>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ ucfirst($coupon->type) }}
                            •
                            @if($coupon->type === 'percentage')
                                {{ rtrim(rtrim(number_format((float) $coupon->value, 2, '.', ''), '0'), '.') }}%
                            @else
                                ₹{{ number_format((float) $coupon->value, 2) }}
                            @endif
                        </p>
                    </div>

                    @if($coupon->is_active)
                        <span class="text-xs font-medium text-emerald-600">Active</span>
                    @else
                        <span class="text-xs font-medium text-rose-600">Inactive</span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span>Min amount</span>
                    <span class="font-medium text-slate-700">₹{{ number_format((float) $coupon->min_amount, 2) }}</span>
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        @click="$dispatch('open-modal'); $wire.openEditModal({{ $coupon->id }})"
                        class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs font-medium"
                    >
                        Edit
                    </button>

                    <button
                        @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $coupon->id }})"
                        class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium"
                    >
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">
                No coupons found.
            </div>
        @endforelse
    </div>

    <div class="flex justify-center">
        {{ $coupons->onEachSide(1)->links() }}
    </div>

    @include('livewire.coupon.coupon-modal')
    @include('livewire.coupon.delete')
</div>

