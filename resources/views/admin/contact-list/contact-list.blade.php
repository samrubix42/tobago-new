<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Contact Management</h1>
            <p class="text-sm text-slate-500 mt-1">Review customer inquiries submitted from the contact page.</p>
        </div>

        <a href="{{ route('contact') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-500 transition">
            <i class="ri-external-link-line text-base"></i>
            View Contact Page
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="lg:col-span-5 relative group">
            <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, email, phone or message..." class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 bg-white transition outline-none text-sm font-medium text-slate-900">
        </div>

        <div class="lg:col-span-4 flex p-1 bg-blue-50/50 rounded-2xl border border-blue-50">
            <button wire:click="$set('status', 'all')" class="flex-1 py-2 text-xs font-bold rounded-xl transition {{ $status === 'all' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-blue-600' }}">
                ALL
            </button>
            <button wire:click="$set('status', 'unread')" class="flex-1 py-2 text-xs font-bold rounded-xl transition {{ $status === 'unread' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-blue-600' }}">
                UNREAD
            </button>
            <button wire:click="$set('status', 'read')" class="flex-1 py-2 text-xs font-bold rounded-xl transition {{ $status === 'read' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-blue-600' }}">
                READ
            </button>
        </div>

        <div class="lg:col-span-3 flex items-center justify-end gap-3 px-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Show</span>
            <select wire:model.live="perPage" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-blue-100 transition">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Contact</th>
                    <th class="px-6 py-4 text-left">Message</th>
                    <th class="px-6 py-4 text-left">Date</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-52">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($contacts as $contact)
                    <tr wire:key="contact-{{ $contact->id }}" class="hover:bg-slate-50 transition">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-9 w-9 flex items-center justify-center rounded-md {{ $contact->is_read ? 'bg-slate-100 text-slate-500' : 'bg-amber-50 text-amber-600' }}">
                                    <i class="{{ $contact->is_read ? 'ri-mail-open-line' : 'ri-mail-unread-line' }} text-base"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $contact->name }}</p>
                                    <p class="text-xs text-slate-400 mt-1">#{{ $contact->id }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <p class="text-slate-700">{{ $contact->phone ?: 'No phone' }}</p>
                            <p class="text-xs text-slate-400 mt-1 break-all">{{ $contact->email ?: 'No email' }}</p>
                        </td>

                        <td class="px-6 py-5">
                            <p class="max-w-md text-slate-600 line-clamp-2">{{ $contact->message }}</p>
                        </td>

                        <td class="px-6 py-5">
                            <p class="text-xs font-medium text-slate-600">{{ $contact->created_at?->format('M d, Y') }}</p>
                            <p class="mt-1 text-[11px] text-slate-400">{{ $contact->created_at?->diffForHumans() }}</p>
                        </td>

                        <td class="px-6 py-5">
                            @if($contact->is_read)
                                <span class="text-emerald-600 text-xs font-medium">Read</span>
                            @else
                                <span class="text-amber-600 text-xs font-medium">Unread</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                @if($contact->is_read)
                                    <button wire:click="markAsUnread({{ $contact->id }})" class="bg-slate-50 text-slate-600 px-3 py-1.5 rounded-md text-xs font-medium">
                                        Unread
                                    </button>
                                @else
                                    <button wire:click="markAsRead({{ $contact->id }})" class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-md text-xs font-medium">
                                        Read
                                    </button>
                                @endif

                                <button wire:click="confirmDelete({{ $contact->id }})" class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">No contact inquiries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sm:hidden space-y-4">
        @forelse($contacts as $contact)
            <div wire:key="mobile-contact-{{ $contact->id }}" class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900">{{ $contact->name }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $contact->phone ?: $contact->email ?: 'No contact detail' }}</p>
                    </div>

                    @if($contact->is_read)
                        <span class="text-xs font-medium text-emerald-600">Read</span>
                    @else
                        <span class="text-xs font-medium text-amber-600">Unread</span>
                    @endif
                </div>

                <p class="text-sm text-slate-600 line-clamp-3">{{ $contact->message }}</p>

                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span>{{ $contact->created_at?->diffForHumans() }}</span>
                    <div class="flex gap-2">
                        @if($contact->is_read)
                            <button wire:click="markAsUnread({{ $contact->id }})" class="bg-slate-50 text-slate-600 px-3 py-1.5 rounded-md text-xs font-medium">Unread</button>
                        @else
                            <button wire:click="markAsRead({{ $contact->id }})" class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-md text-xs font-medium">Read</button>
                        @endif

                        <button wire:click="confirmDelete({{ $contact->id }})" class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">
                No contact inquiries found.
            </div>
        @endforelse
    </div>

    <div class="flex justify-center">
        {{ $contacts->onEachSide(1)->links() }}
    </div>

    @if($deleteId)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex items-start gap-4">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                        <i class="ri-delete-bin-line text-xl"></i>
                    </span>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Delete inquiry?</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">This contact message will be permanently removed from admin records.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button wire:click="delete" class="rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-500 transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
