<div class="w-full space-y-6 pb-6">
    <!-- Welcome Header -->
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Tobac-Go Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500 font-normal">Real-time overview of your store's sales, catalog, and operations.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Store Live
            </span>
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <i class="ri-external-link-line"></i>
                View Storefront
            </a>
        </div>
    </div>

    <!-- Core Metrics Grid -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Revenue Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Total Revenue</p>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="ri-wallet-3-line text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-3xl font-semibold text-slate-900">&#8377;{{ number_format($this->stats['revenue'], 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">From paid & completed orders</p>
        </div>

        <!-- Orders Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Total Orders</p>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="ri-shopping-bag-2-line text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $this->stats['orders_count'] }}</p>
            <p class="mt-1 text-xs text-slate-500">All customer checkouts</p>
        </div>

        <!-- Products Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Active Products</p>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="ri-price-tag-3-line text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $this->stats['products_count'] }}</p>
            <p class="mt-1 text-xs text-slate-500">Listed shisha & accessories</p>
        </div>

        <!-- Customers Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Total Customers</p>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <i class="ri-user-5-line text-lg"></i>
                </span>
            </div>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $this->stats['customers_count'] }}</p>
            <p class="mt-1 text-xs text-slate-500">Excluding admin accounts</p>
        </div>
    </div>

    <!-- Order Status Summary -->
    <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                <i class="ri-time-line text-lg"></i>
            </span>
            <div>
                <p class="text-xs font-medium text-slate-500">Pending</p>
                <p class="text-lg font-bold text-slate-900 leading-tight">{{ $this->orderStatusStats['pending'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="ri-settings-4-line text-lg"></i>
            </span>
            <div>
                <p class="text-xs font-medium text-slate-500">Processing</p>
                <p class="text-lg font-bold text-slate-900 leading-tight">{{ $this->orderStatusStats['processing'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <i class="ri-checkbox-circle-line text-lg"></i>
            </span>
            <div>
                <p class="text-xs font-medium text-slate-500">Completed</p>
                <p class="text-lg font-bold text-slate-900 leading-tight">{{ $this->orderStatusStats['shipped'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <i class="ri-close-circle-line text-lg"></i>
            </span>
            <div>
                <p class="text-xs font-medium text-slate-500">Cancelled</p>
                <p class="text-lg font-bold text-slate-900 leading-tight">{{ $this->orderStatusStats['cancelled'] }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Recent Orders & Quick Actions (lg:col-span-2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Recent Orders -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 p-6 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Recent Orders</h2>
                    <a href="{{ route('admin.orders') }}" wire:navigate class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">
                        View All Orders
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4 text-left">Order #</th>
                                <th class="px-6 py-4 text-left">Customer</th>
                                <th class="px-6 py-4 text-left">Total</th>
                                <th class="px-6 py-4 text-left">Payment</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 w-20"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($this->recentOrders as $order)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-900">#{{ $order->order_number }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-900">&#8377;{{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/10' : 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/10' }}">
                                            {{ ucfirst($order->payment_status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-emerald-50 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700') }}">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.manage', $order->id) }}" wire:navigate class="inline-flex h-8 items-center justify-center rounded-lg border border-slate-200 px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        <i class="ri-inbox-line text-2xl"></i>
                                        <p class="mt-2 text-sm">No orders recorded yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Management Grid -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-6">Quick Management Dashboard</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('admin.products.index') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i class="ri-price-tag-3-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Products</p>
                            <p class="text-xs text-slate-500">Edit and add items</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.categories') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                            <i class="ri-folder-3-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Categories</p>
                            <p class="text-xs text-slate-500">Build hierarchies</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventory') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <i class="ri-archive-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Inventory</p>
                            <p class="text-xs text-slate-500">Update stock levels</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.blogs') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <i class="ri-article-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Blogs</p>
                            <p class="text-xs text-slate-500">Guides & shisha news</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.coupons') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                            <i class="ri-coupon-3-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Coupons</p>
                            <p class="text-xs text-slate-500">Discounts & rules</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.testimonials') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                            <i class="ri-chat-3-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Testimonials</p>
                            <p class="text-xs text-slate-500">Reviews & approvals</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.seo-pages') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                            <i class="ri-search-eye-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">SEO Meta</p>
                            <p class="text-xs text-slate-500">Improve page search rank</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings') }}" wire:navigate class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-slate-50">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-600">
                            <i class="ri-settings-3-line text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Settings</p>
                            <p class="text-xs text-slate-500">Config & phone numbers</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: Inventory Alerts & Blogs & Credentials (lg:col-span-1) -->
        <div class="space-y-6">
            <!-- Low Stock Alerts -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Stock Alerts</h2>
                    <a href="{{ route('admin.inventory') }}" wire:navigate class="text-xs font-semibold text-amber-600 hover:text-amber-500 transition">
                        Manage Stock
                    </a>
                </div>

                <div class="space-y-3.5 divide-y divide-slate-100">
                    @forelse($this->lowStockProducts as $index => $product)
                        <div class="flex items-center justify-between gap-3 text-sm {{ $index > 0 ? 'pt-3.5' : '' }}">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-slate-900">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">SKU: {{ $product->sku ?? 'N/A' }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $product->stock == 0 ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $product->stock == 0 ? 'Out of Stock' : $product->stock . ' left' }}
                            </span>
                        </div>
                    @empty
                        <div class="py-4 text-center text-xs text-slate-400">
                            <i class="ri-check-double-line text-lg text-emerald-500"></i>
                            <p class="mt-1">All products are well stocked.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Blogs -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Recent Guides</h2>
                    <a href="{{ route('admin.blogs') }}" wire:navigate class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">
                        All Guides
                    </a>
                </div>

                <div class="space-y-4 divide-y divide-slate-100">
                    @forelse($this->recentBlogs as $index => $post)
                        <div class="flex items-start gap-3 text-sm {{ $index > 0 ? 'pt-4' : '' }}">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . ltrim($post->featured_image, '/')) }}" alt="" class="h-10 w-10 shrink-0 rounded-lg object-cover">
                            @else
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                                    <i class="ri-image-line"></i>
                                </span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('admin.blogs.edit', $post->id) }}" wire:navigate class="truncate font-semibold text-slate-900 hover:text-indigo-600 transition block">
                                    {{ $post->title }}
                                </a>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $post->category?->title ?? 'General' }} &bull; {{ optional($post->created_at)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-xs text-slate-400">
                            <p>No guides added yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Credentials Box -->
            <div class="rounded-2xl border border-slate-250 bg-slate-950 p-6 text-slate-100 shadow-sm">
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <i class="ri-key-line text-sm"></i>
                    Admin Credentials Seeder
                </div>
                <h2 class="mt-3 text-lg font-bold text-white">Default Store Admin</h2>
                <div class="mt-4 space-y-2.5 text-xs text-slate-300 bg-white/5 rounded-xl p-4 border border-white/5">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Email:</span>
                        <span class="font-mono text-white select-all">admin@tobacgo.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Password:</span>
                        <span class="font-mono text-white select-all">admin12345</span>
                    </div>
                </div>
                <p class="mt-4 text-xs leading-relaxed text-slate-400">You can safely remove this default credential card once production users have been created in the database.</p>
            </div>
        </div>
    </div>
</div>
