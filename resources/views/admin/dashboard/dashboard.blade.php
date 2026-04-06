<div class="max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Store Status</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">Live</p>
            <p class="mt-2 text-sm text-slate-500">Admin access is working and the panel is ready for content management.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Categories</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ \App\Models\Category::count() }}</p>
            <p class="mt-2 text-sm text-slate-500">Manage category hierarchy, images, status, and SEO fields.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Brands</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">Soon</p>
            <p class="mt-2 text-sm text-slate-500">Brand management route is reserved and can be built next.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Products</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">Soon</p>
            <p class="mt-2 text-sm text-slate-500">Product management route is ready for the next admin module.</p>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Quick Actions</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <a href="{{ route('admin.categories') }}" class="rounded-2xl border border-slate-200 p-5 transition hover:border-slate-300 hover:bg-slate-50">
                    <i class="ri-folder-3-line text-2xl text-blue-600"></i>
                    <p class="mt-4 text-base font-semibold text-slate-900">Manage Categories</p>
                    <p class="mt-2 text-sm text-slate-500">Add, edit, delete, and arrange store categories.</p>
                </a>
                <a href="{{ route('home') }}" class="rounded-2xl border border-slate-200 p-5 transition hover:border-slate-300 hover:bg-slate-50">
                    <i class="ri-store-2-line text-2xl text-amber-600"></i>
                    <p class="mt-4 text-base font-semibold text-slate-900">View Storefront</p>
                    <p class="mt-2 text-sm text-slate-500">Open the public website and review the shopping experience.</p>
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-slate-900 p-6 text-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-400">Admin Login Seeder</p>
            <h2 class="mt-3 text-2xl font-semibold text-white">Default Credentials</h2>
            <div class="mt-5 space-y-2 text-sm text-slate-300">
                <p>Email: <span class="font-semibold text-white">admin@tobacgo.com</span></p>
                <p>Password: <span class="font-semibold text-white">admin12345</span></p>
            </div>
            <p class="mt-5 text-sm leading-7 text-slate-400">You can change this user after seeding, but this gives you a working admin login immediately.</p>
        </div>
    </div>
</div>
