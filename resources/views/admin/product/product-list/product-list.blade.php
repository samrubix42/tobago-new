<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">
                Product Management
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                View and manage your store's product inventory.
            </p>
        </div>

        <a href="{{ route('admin.products.add') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-500 transition"
        >
            <i class="ri-add-line text-base"></i>
            Add Product
        </a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 focus-within:text-blue-500 transition-colors">
                <i class="ri-search-line"></i>
            </span>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search products..."
                class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition duration-200 shadow-sm sm:shadow-none"
            >
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto sm:ml-auto">
            <label for="per-page" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Show</label>
            <select
                id="per-page"
                wire:model.live="perPage"
                class="h-10 rounded-md border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500"
            >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-xs text-slate-500">per page</span>
        </div>
    </div>

    <!-- Main Table (Desktop) -->
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in text-nowrap">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Product</th>
                    <th class="px-6 py-4 text-left font-semibold">Category</th>
                    <th class="px-6 py-4 text-left font-semibold">Price</th>
                    <th class="px-6 py-4 text-left font-semibold">Stock</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-right font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr wire:key="product-{{ $product->id }}" class="hover:bg-slate-50/80 transition duration-150">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                                    @php $primaryImg = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                                    @if($primaryImg)
                                        <img src="{{ asset('storage/' . $primaryImg->image) }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-300 bg-slate-50">
                                            <i class="ri-image-line text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 truncate max-w-[200px]" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-xs text-slate-400 font-mono tracking-tight mt-0.5 truncate max-w-[150px]">
                                        {{ $product->slug }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            @if($product->category)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    <i class="ri-folder-line text-[10px]"></i>
                                    {{ $product->category->title }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">Uncategorized</span>
                            @endif
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">₹{{ number_format($product->selling_price, 2) }}</span>
                                @if($product->compare_price > $product->selling_price)
                                    <span class="text-xs text-slate-400 line-through decoration-rose-400/50 decoration-1.5">₹{{ number_format($product->compare_price, 2) }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <div class="flex flex-col">
                                    <span class="font-medium {{ $product->stock <= ($product->hurry_stock ?? 5) ? 'text-rose-600' : 'text-slate-700' }}">
                                        {{ $product->stock }}
                                        <span class="text-[10px] text-slate-400 uppercase tracking-wider ml-0.5">items</span>
                                    </span>
                                    @if($product->stock <= ($product->hurry_stock ?? 5))
                                        <span class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-0.5 leading-none px-1.5 py-0.5 bg-rose-50 rounded select-none">Low Stock</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <select
                                wire:change="updateStatus({{ $product->id }}, $event.target.value)"
                                class="w-32 rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500"
                            >
                                <option value="active" @selected($product->status === 'active')>Active</option>
                                <option value="inactive" @selected($product->status === 'inactive')>Inactive</option>
                                <option value="draft" @selected($product->status === 'draft')>Draft</option>
                            </select>
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="h-8 w-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition duration-200 shadow-sm"
                                   title="Edit Product"
                                >
                                    <i class="ri-edit-line text-sm"></i>
                                </a>

                                <button
                                    @click="$dispatch('open-delete-product-modal', { id: {{ $product->id }}, name: '{{ addslashes($product->name) }}' })"
                                    class="h-8 w-8 inline-flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition duration-200 shadow-sm"
                                    title="Delete Product"
                                >
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="h-20 w-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-slate-200 shadow-inner">
                                    <i class="ri-box-3-line text-4xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">No products found</h3>
                                <p class="text-xs text-slate-500 mt-1">Try adjusting your search or add a new product to get started.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Mobile View -->
    <div class="sm:hidden space-y-4">
        @forelse($products as $product)
            <div wire:key="mobile-product-{{ $product->id }}" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-4 animate-fade-in active:scale-[0.98] transition">
                <div class="flex items-start gap-4">
                    <div class="h-16 w-16 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                        @php $primaryImg = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                        @if($primaryImg)
                            <img src="{{ asset('storage/' . $primaryImg->image) }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                <i class="ri-image-line text-xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5 line-clamp-1 italic">{{ $product->category->title ?? 'No Category' }}</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-sm font-bold text-slate-900">₹{{ number_format($product->selling_price, 2) }}</span>
                            @if($product->compare_price > $product->selling_price)
                                <span class="text-[10px] text-slate-400 line-through decoration-rose-400/50">₹{{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-1">Stock Status</span>
                        <div class="flex items-center gap-2">
                             <span class="text-xs font-bold {{ $product->stock <= ($product->hurry_stock ?? 5) ? 'text-rose-600' : 'text-slate-700' }}">
                                {{ $product->stock }} Units
                            </span>
                            @if($product->stock <= ($product->hurry_stock ?? 5))
                                <span class="h-2 w-2 rounded-full bg-rose-500 animate-ping"></span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <select
                            wire:change="updateStatus({{ $product->id }}, $event.target.value)"
                            class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500"
                        >
                            <option value="active" @selected($product->status === 'active')>Active</option>
                            <option value="inactive" @selected($product->status === 'inactive')>Inactive</option>
                            <option value="draft" @selected($product->status === 'draft')>Draft</option>
                        </select>

                        <div class="flex gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm active:scale-95"
                        >
                            <i class="ri-edit-line"></i>
                        </a>
                        <button
                            @click="$dispatch('open-delete-product-modal', { id: {{ $product->id }}, name: '{{ addslashes($product->name) }}' })"
                            class="h-10 w-10 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition shadow-sm active:scale-95"
                        >
                            <i class="ri-delete-bin-line"></i>
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl py-12 px-6 text-center text-slate-400 animate-fade-in shadow-inner">
                <i class="ri-shopping-basket-2-line text-4xl mb-3 block opacity-50"></i>
                <p class="text-sm font-medium">No products match your search</p>
                <button wire:click="$set('search', '')" class="text-xs text-blue-600 font-bold mt-2 hover:underline">Clear search filters</button>
            </div>
        @endforelse

        <div class="pt-1">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Product Delete Modal -->
    <div 
        x-data="{ show: false, name: '', id: null }"
        x-show="show"
        @open-delete-product-modal.window="show = true; name = $event.detail.name; id = $event.detail.id"
        @close-delete-product-modal.window="show = false"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4"
        style="display: none;"
    >
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="show = false"></div>
        <div class="bg-white rounded-2xl shadow-2xl relative max-w-sm w-full p-8 text-center transform transition-all animate-modal-enter">
            <div class="h-20 w-20 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-100">
                <i class="ri-delete-bin-2-line text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Delete Product?</h3>
            <p class="text-sm text-slate-500 leading-relaxed mb-8">
                You are about to delete <span class="font-bold text-slate-900" x-text="name"></span>. 
                All associated data and images will be permanently removed.
            </p>
            <div class="flex gap-4">
                <button @click="show = false" class="flex-1 px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition duration-200">
                    Cancel
                </button>
                <button @click="$wire.deleteProduct(id); show = false" class="flex-1 px-6 py-3 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md hover:shadow-lg transition duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.4s ease-out forwards;
}
@keyframes modal-enter {
    from { opacity: 0; transform: scale(0.95) translateY(-20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-enter {
    animation: modal-enter 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
</style>