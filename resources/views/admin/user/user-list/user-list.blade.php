<div class="p-6 lg:p-10 space-y-8 text-black">

    <!-- 🔷 Heading Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage all registered accounts</p>
        </div>
    </div>

    <!-- 🔷 Filters & Search -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center bg-white p-4 rounded-2xl border border-slate-200 shadow-none">
        
        <!-- Search -->
        <div class="lg:col-span-5 relative group">
            <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                 <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email or phone..."
                     class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 bg-white transition outline-none text-sm font-medium text-gray-900">
        </div>

        <!-- Role Filter -->
        <div class="lg:col-span-4 flex p-1 bg-blue-50/50 rounded-2xl border border-blue-50">
            <button wire:click="$set('role', 'all')" 
                    class="flex-1 py-2 text-xs font-bold rounded-xl transition {{ $role === 'all' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-blue-600' }}">
                ALL
            </button>
            <button wire:click="$set('role', 'admin')" 
                    class="flex-1 py-2 text-xs font-bold rounded-xl transition {{ $role === 'admin' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-blue-600' }}">
                ADMINS
            </button>
            <button wire:click="$set('role', 'user')" 
                    class="flex-1 py-2 text-xs font-bold rounded-xl transition {{ $role === 'user' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-blue-600' }}">
                CUSTOMERS
            </button>
        </div>

        <!-- Per Page -->
        <div class="lg:col-span-3 flex items-center justify-end gap-3 px-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Show</span>
            <select wire:model.live="perPage" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-gray-900 outline-none focus:ring-4 focus:ring-blue-100 transition">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <!-- 🔷 User Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">User Details</th>
                        <th class="px-6 py-4 text-left">Contact</th>
                        <th class="px-6 py-4 text-left">Joined At</th>
                        <th class="px-6 py-4 text-left text-center">Role</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="group hover:bg-slate-50 transition-colors">
                            <!-- Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-md">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-tight">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <!-- Contact -->
                            <td class="px-6 py-4">
                                <p class="text-xs font-semibold text-gray-600">{{ $user->phone ?? 'NO PHONE' }}</p>
                                @if($user->google_id)
                                    <div class="flex items-center gap-1 mt-1">
                                        <i class="ri-google-fill text-blue-500 text-xs"></i>
                                        <span class="text-[9px] font-bold text-blue-400 uppercase">Google Linked</span>
                                    </div>
                                @endif
                            </td>
                            <!-- Date -->
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-500">{{ $user->created_at->format('M d, Y') }}</p>
                                <p class="text-[10px] text-gray-300">{{ $user->created_at->diffForHumans() }}</p>
                            </td>
                            <!-- Role -->
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleAdmin({{ $user->id }})" 
                                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase transition
                                        {{ $user->is_admin ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $user->is_admin ? 'Admin' : 'Customer' }}
                                </button>
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 pr-2">
                                    <button
                                        @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $user->id }})"
                                        class="p-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition shadow-sm">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center">
                                        <i class="ri-user-search-line text-3xl text-blue-200"></i>
                                    </div>
                                    <p class="text-gray-400 text-sm font-medium">No users found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/20">
            {{ $users->links() }}
        </div>
    </div>

    @include('livewire.user.delete')

</div>