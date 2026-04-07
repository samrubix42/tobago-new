<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $role = 'all'; // all, admin, user
    public ?int $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteUser($id)
    {
        $this->confirmDelete($id);
    }

    public function confirmDelete(?int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(?int $id = null): void
    {
        $id = $id ?? $this->deleteId;

        if (! $id) {
            return;
        }

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('toast-show', [
                'message' => 'You cannot delete yourself!',
                'type' => 'error',
                'position' => 'top-right',
            ]);

            $this->dispatch('close-delete-modal');
            $this->deleteId = null;
            return;
        }

        $user->delete();

        $this->dispatch('toast-show', [
            'message' => 'User deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-delete-modal');
        $this->deleteId = null;
        $this->dispatch('refresh-user-list');
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_admin' => !$user->is_admin]);
        $this->dispatch('notify', 'Role updated.');
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->role === 'admin') {
            $query->where('is_admin', true);
        } elseif ($this->role === 'user') {
            $query->where('is_admin', false);
        }

        return view('admin.user.user-list.user-list', [
            'users' => $query->latest()->paginate($this->perPage)
        ]);
    }
};