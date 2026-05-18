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

    // Edit fields
    public ?int $editId = null;
    public string $editName = '';
    public string $editEmail = '';
    public string $editPhone = '';
    public bool $editIsAdmin = false;

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

    public function editUser(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editPhone = $user->phone ?? '';
        $this->editIsAdmin = (bool) $user->is_admin;

        $this->dispatch('open-edit-modal');
    }

    public function saveUser(): void
    {
        $this->validate([
            'editName' => ['required', 'string', 'min:2', 'max:255'],
            'editEmail' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->editId],
            'editPhone' => ['nullable', 'string', 'max:50'],
            'editIsAdmin' => ['required', 'boolean'],
        ], [], [
            'editName' => 'name',
            'editEmail' => 'email',
            'editPhone' => 'phone number',
            'editIsAdmin' => 'role',
        ]);

        $user = User::findOrFail($this->editId);

        // Prevent admin from removing their own admin rights
        if ($user->id === auth()->id() && !$this->editIsAdmin) {
            $this->dispatch('toast-show', [
                'message' => 'You cannot revoke your own admin rights!',
                'type' => 'error',
                'position' => 'top-right',
            ]);
            return;
        }

        $user->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
            'phone' => $this->editPhone ?: null,
            'is_admin' => $this->editIsAdmin,
        ]);

        $this->dispatch('toast-show', [
            'message' => 'User updated successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-edit-modal');
        $this->resetEditFields();
    }

    private function resetEditFields(): void
    {
        $this->editId = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editPhone = '';
        $this->editIsAdmin = false;
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