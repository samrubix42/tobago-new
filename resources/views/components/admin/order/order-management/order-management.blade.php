<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Order Details</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $order->order_number }} • #{{ $order->id }}</p>
        </div>
        <a href="{{ route('admin.orders') }}" wire:navigate class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
            <i class="ri-arrow-left-line"></i>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="rounded-xl border border-slate-200 bg-white p-3.5">
            <p class="text-xs uppercase tracking-wider text-slate-500">Status</p>
            <p class="text-sm font-semibold mt-1 text-slate-900">{{ ucwords(str_replace('-', ' ', $order->status)) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-3.5">
            <p class="text-xs uppercase tracking-wider text-slate-500">Payment</p>
            <p class="text-sm font-semibold mt-1 text-slate-900">{{ strtoupper($order->payment_method) }} • {{ ucfirst($order->payment_status) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-3.5">
            <p class="text-xs uppercase tracking-wider text-slate-500">Delivery</p>
            <p class="text-sm font-semibold mt-1 text-slate-900">{{ $order->delivery_type === 'third_party' ? '3rd Party' : 'In-hand' }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-3.5">
            <p class="text-xs uppercase tracking-wider text-slate-500">Items</p>
            <p class="text-sm font-semibold mt-1 text-slate-900">{{ $order->items->sum('quantity') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-4">Ordered Items</h2>

                <div class="hidden md:block overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full text-sm bg-white text-nowrap">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-3 py-2.5 text-left font-semibold">Product</th>
                                <th class="px-3 py-2.5 text-left font-semibold">SKU</th>
                                <th class="px-3 py-2.5 text-right font-semibold">Qty</th>
                                <th class="px-3 py-2.5 text-right font-semibold">Unit Price</th>
                                <th class="px-3 py-2.5 text-right font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                @php
                                    $image = $item->product_image
                                        ?? $item->product?->images?->firstWhere('is_primary', true)?->image
                                        ?? $item->product?->images?->first()?->image;
                                @endphp
                                <tr>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="h-11 w-11 rounded-md border border-slate-200 bg-slate-50 overflow-hidden shrink-0 flex items-center justify-center">
                                                @if($image)
                                                    <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . ltrim($image, '/')) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                                @else
                                                    <img src="{{ asset('images/hero.png') }}" alt="{{ $item->product_name }}" class="h-8 object-contain opacity-80">
                                                @endif
                                            </div>
                                            <p class="text-slate-900 font-medium truncate">{{ $item->product_name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-xs text-slate-600">{{ $item->sku ?: '-' }}</td>
                                    <td class="px-3 py-3 text-right text-slate-700">{{ $item->quantity }}</td>
                                    <td class="px-3 py-3 text-right text-slate-700">Rs {{ number_format((float) $item->price, 2) }}</td>
                                    <td class="px-3 py-3 text-right font-semibold text-slate-900">Rs {{ number_format((float) $item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-2.5">
                    @foreach($order->items as $item)
                        @php
                            $image = $item->product_image
                                ?? $item->product?->images?->firstWhere('is_primary', true)?->image
                                ?? $item->product?->images?->first()?->image;
                        @endphp
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="flex items-start gap-3">
                                <div class="h-12 w-12 rounded-md border border-slate-200 bg-white overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($image)
                                        <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . ltrim($image, '/')) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                    @else
                                        <img src="{{ asset('images/hero.png') }}" alt="{{ $item->product_name }}" class="h-8 object-contain opacity-80">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-900 truncate">{{ $item->product_name }}</p>
                                    <p class="text-xs text-slate-500 mt-1">SKU: {{ $item->sku ?: '-' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">Qty: {{ $item->quantity }} • Rs {{ number_format((float) $item->price, 2) }}</p>
                                </div>
                                <p class="text-sm font-semibold text-slate-900">Rs {{ number_format((float) $item->total, 2) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-4">Tracking Logs</h2>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full text-sm bg-white text-nowrap">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-3 py-2.5 text-left font-semibold">Status</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Note</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Source</th>
                                <th class="px-3 py-2.5 text-left font-semibold">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($order->statusLogs as $log)
                                <tr>
                                    <td class="px-3 py-3 text-slate-900 font-medium">{{ ucwords(str_replace('-', ' ', $log->status)) }}</td>
                                    <td class="px-3 py-3 text-slate-600">{{ $log->note ?: 'Status updated.' }}</td>
                                    <td class="px-3 py-3 text-slate-500 text-xs uppercase">{{ $log->source ?? 'system' }}</td>
                                    <td class="px-3 py-3 text-slate-500 text-xs">{{ optional($log->logged_at)->format('d M Y, h:i A') ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-center text-sm text-slate-500">No logs found yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <aside class="space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 space-y-3 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Order Summary</h2>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs">
                    <p class="text-slate-500">Customer</p>
                    <p class="text-slate-900 font-semibold mt-1">{{ $order->customer_name }}</p>
                    <p class="text-slate-600 mt-0.5">{{ $order->customer_phone }}</p>
                </div>
                <div class="space-y-1.5 text-sm">
                    <div class="flex items-center justify-between text-slate-600"><span>Subtotal</span><span>Rs {{ number_format((float) $order->subtotal, 2) }}</span></div>
                    <div class="flex items-center justify-between text-slate-600"><span>Discount</span><span>- Rs {{ number_format((float) $order->discount, 2) }}</span></div>
                    <div class="flex items-center justify-between text-slate-600"><span>Shipping</span><span>Rs {{ number_format((float) $order->shipping_amount, 2) }}</span></div>
                    <div class="border-t border-slate-200 pt-2 flex items-center justify-between font-semibold text-slate-900"><span>Total</span><span>Rs {{ number_format((float) $order->total, 2) }}</span></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 space-y-3 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Payment Details</h2>
                <div class="space-y-3">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs">
                        <p class="text-slate-500 uppercase tracking-wider">Method & Status</p>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-slate-900 font-semibold">{{ strtoupper($order->payment_method) }}</p>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    @if($order->payment_gateway_transaction_id || $order->payment_gateway_order_id)
                        <div class="space-y-2 text-xs">
                            @if($order->payment_gateway)
                                <div class="flex items-center justify-between text-slate-600">
                                    <span>Gateway</span>
                                    <span class="text-slate-900 font-medium">{{ ucfirst($order->payment_gateway) }}</span>
                                </div>
                            @endif
                            @if($order->payment_gateway_order_id)
                                <div class="flex flex-col gap-1 py-1 border-t border-slate-100">
                                    <span class="text-slate-500">Gateway Order ID</span>
                                    <span class="text-slate-900 font-mono break-all">{{ $order->payment_gateway_order_id }}</span>
                                </div>
                            @endif
                            @if($order->payment_gateway_transaction_id)
                                <div class="flex flex-col gap-1 py-1 border-t border-slate-100">
                                    <span class="text-slate-500">Transaction ID</span>
                                    <span class="text-slate-900 font-mono break-all">{{ $order->payment_gateway_transaction_id }}</span>
                                </div>
                            @endif
                            @if($order->payment_verified_at)
                                <div class="flex items-center justify-between text-slate-600 pt-1 border-t border-slate-100">
                                    <span>Verified At</span>
                                    <span class="text-slate-900">{{ $order->payment_verified_at->format('d M Y, h:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($order->payment_failure_reason)
                        <div class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-xs">
                            <p class="text-rose-600 font-semibold mb-1">Failure Reason</p>
                            <p class="text-rose-700">{{ $order->payment_failure_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <form wire:submit="updateOrder" class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 space-y-3 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Update Order</h2>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                    <select wire:model="status" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="packed">Packed</option>
                        <option value="shipped">Shipped</option>
                        <option value="on-the-way">On the way</option>
                        <option value="delivered">Delivered</option>
                        <option value="returned">Returned</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Delivery Type</label>
                    <select wire:model.live="deliveryType" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none">
                        <option value="in_hand_delivery">In-hand Delivery</option>
                        <option value="third_party">3rd Party Courier</option>
                    </select>
                    @error('deliveryType') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                @if($deliveryType === 'third_party')
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Courier Partner</label>
                        <input type="text" wire:model.defer="deliveryPartner" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                        @error('deliveryPartner') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">AWB Number</label>
                        <input type="text" wire:model.defer="awbNumber" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                        @error('awbNumber') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tracking URL</label>
                        <input type="url" wire:model.defer="trackingUrl" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                        @error('trackingUrl') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Delivery Boy Name</label>
                        <input type="text" wire:model.defer="deliveryBoyName" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                        @error('deliveryBoyName') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Delivery Boy Phone</label>
                        <input type="text" wire:model.defer="deliveryBoyPhone" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                        @error('deliveryBoyPhone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Estimated Delivery</label>
                    <input type="datetime-local" wire:model.defer="estimatedDeliveryAt" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                    @error('estimatedDeliveryAt') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status Note</label>
                    <textarea wire:model.defer="statusNote" rows="3" placeholder="Optional note for timeline" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"></textarea>
                    @error('statusNote') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="updateOrder" class="w-full rounded-md bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-500 disabled:opacity-60">
                    <span wire:loading.remove wire:target="updateOrder">Save Changes</span>
                    <span wire:loading wire:target="updateOrder">Saving...</span>
                </button>
            </form>
        </aside>
    </div>
</div>