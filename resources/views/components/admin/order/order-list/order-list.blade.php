<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Order Management</h1>
            <p class="text-sm text-slate-500 mt-1">Track, filter, and manage customer orders.</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="relative w-full sm:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="ri-search-line"></i>
            </span>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search by order no, customer, phone..."
                class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none"
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
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span class="text-xs text-slate-500">per page</span>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <select wire:model.live="status" class="rounded-md border border-slate-300 px-2.5 py-2 text-xs font-medium text-slate-700 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="packed">Packed</option>
            <option value="shipped">Shipped</option>
            <option value="on-the-way">On the way</option>
            <option value="delivered">Delivered</option>
            <option value="returned">Returned</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <select wire:model.live="deliveryType" class="rounded-md border border-slate-300 px-2.5 py-2 text-xs font-medium text-slate-700 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none">
            <option value="all">All Delivery</option>
            <option value="in_hand_delivery">In-hand</option>
            <option value="third_party">3rd Party</option>
        </select>

        <button wire:click="clearFilters" class="inline-flex rounded-md border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
            Reset
        </button>
    </div>

    <div class="hidden md:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-nowrap">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Order</th>
                    <th class="px-4 py-3 text-left font-semibold">Customer</th>
                    <th class="px-4 py-3 text-left font-semibold">Items</th>
                    <th class="px-4 py-3 text-left font-semibold">Total</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    <tr wire:key="order-row-{{ $order->id }}" class="hover:bg-slate-50/80 transition duration-150">
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">#{{ $order->id }} • {{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="text-slate-900">{{ $order->customer_name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-slate-700">{{ $order->items_count }}</td>
                        <td class="px-4 py-3.5 font-semibold text-slate-900">Rs {{ number_format((float) $order->total, 2) }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-[11px] {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ ucwords(str_replace('-', ' ', $order->status)) }}</span>
                                <select
                                    wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                    class="rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-[11px] text-slate-700 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none"
                                >
                                    <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                    <option value="confirmed" @selected($order->status === 'confirmed')>Confirmed</option>
                                    <option value="packed" @selected($order->status === 'packed')>Packed</option>
                                    <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                                    <option value="on-the-way" @selected($order->status === 'on-the-way')>On the way</option>
                                    <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                                    <option value="returned" @selected($order->status === 'returned')>Returned</option>
                                    <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                                </select>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="inline-flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.orders.manage', $order->id) }}" wire:navigate class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                    Manage
                                    <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-14 text-center text-slate-500">No orders found for current filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="md:hidden space-y-3">
        @forelse($orders as $order)
            <article wire:key="mobile-order-{{ $order->id }}" class="bg-white border border-slate-200 rounded-2xl p-4 space-y-3">
                <div>
                    <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">#{{ $order->id }} • {{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
                </div>

                <div class="text-sm text-slate-700">
                    <p>{{ $order->customer_name }}</p>
                    <p class="text-xs text-slate-500">{{ $order->customer_phone }}</p>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                        <p class="text-slate-500">Items</p>
                        <p class="text-slate-900 font-semibold mt-1">{{ $order->items_count }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                        <p class="text-slate-500">Total</p>
                        <p class="text-slate-900 font-semibold mt-1">Rs {{ number_format((float) $order->total, 2) }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ ucwords(str_replace('-', ' ', $order->status)) }}</span>
                    <a href="{{ route('admin.orders.manage', $order->id) }}" wire:navigate class="text-xs text-blue-600 font-medium">Manage</a>
                </div>

                <div class="grid grid-cols-1 gap-2">
                    <select
                        wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                        class="rounded-md border border-slate-300 bg-white px-2.5 py-2 text-xs text-slate-700 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none"
                    >
                        <option value="pending" @selected($order->status === 'pending')>Pending</option>
                        <option value="confirmed" @selected($order->status === 'confirmed')>Confirmed</option>
                        <option value="packed" @selected($order->status === 'packed')>Packed</option>
                        <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                        <option value="on-the-way" @selected($order->status === 'on-the-way')>On the way</option>
                        <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                        <option value="returned" @selected($order->status === 'returned')>Returned</option>
                        <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                    </select>
                </div>
            </article>
        @empty
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-8 text-center text-sm text-slate-500">
                No orders found for current filters.
            </div>
        @endforelse
    </div>

    <div>
        {{ $orders->links() }}
    </div>
</div>