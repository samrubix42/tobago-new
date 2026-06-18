<?php

use App\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public int $perPage = 10;
    public ?int $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function markAsRead(int $contactId): void
    {
        Contact::query()->whereKey($contactId)->update(['is_read' => true]);

        $this->dispatch('toast-show', [
            'message' => 'Contact marked as read.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function markAsUnread(int $contactId): void
    {
        Contact::query()->whereKey($contactId)->update(['is_read' => false]);

        $this->dispatch('toast-show', [
            'message' => 'Contact marked as unread.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function confirmDelete(int $contactId): void
    {
        $this->deleteId = $contactId;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
    }

    public function delete(?int $contactId = null): void
    {
        $id = $contactId ?? $this->deleteId;

        if (! $id) {
            return;
        }

        Contact::query()->whereKey($id)->delete();

        $this->deleteId = null;
        $this->resetPage();

        $this->dispatch('toast-show', [
            'message' => 'Contact inquiry deleted.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function render()
    {
        $contactsQuery = Contact::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($nested) {
                    $nested->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('message', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status === 'read', fn ($query) => $query->where('is_read', true))
            ->when($this->status === 'unread', fn ($query) => $query->where('is_read', false));

        return view('admin.contact-list.contact-list', [
            'contacts' => (clone $contactsQuery)->latest()->paginate($this->perPage),
        ]);
    }
};
