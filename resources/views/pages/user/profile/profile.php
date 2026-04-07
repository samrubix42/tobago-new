<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app')] #[Title('My Profile')] class extends Component
{
    use WithFileUploads;

    public string $name        = '';
    public string $email       = '';
    public string $phone       = '';
    public ?string $currentAvatar = null;
    public $photo = null;

    // Password change
    public string $current_password     = '';
    public string $new_password         = '';
    public string $new_password_confirm = '';

    public bool $saved         = false;
    public bool $passwordSaved = false;

    public function mount(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $user = Auth::user();
        $this->name          = $user->name;
        $this->email         = $user->email;
        $this->phone         = $user->phone ?? '';
        $this->currentAvatar = $user->avatar;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $avatarUrl = $user->avatar;

        if ($this->photo) {
            // Delete old stored avatar if it's not a URL (i.e. not a Google avatar)
            if ($avatarUrl && !str_starts_with($avatarUrl, 'http')) {
                Storage::disk('public')->delete($avatarUrl);
            }
            $avatarUrl = $this->photo->store('avatars', 'public');
            $this->currentAvatar = Storage::url($avatarUrl);
        }

        $user->update([
            'name'   => $this->name,
            'email'  => $this->email,
            'phone'  => $this->phone ?: null,
            'avatar' => $this->photo ? $avatarUrl : $user->avatar,
        ]);

        $this->photo = null;
        $this->saved = true;
        $this->dispatch('profile-saved');
    }

    public function updatePassword(): void
    {
        $user = Auth::user();

        $this->validate([
            'current_password'     => ['required'],
            'new_password'         => ['required', 'string', 'min:8', 'confirmed:new_password_confirm'],
            'new_password_confirm' => ['required'],
        ]);

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->update(['password' => $this->new_password]);

        $this->current_password     = '';
        $this->new_password         = '';
        $this->new_password_confirm = '';
        $this->passwordSaved        = true;
    }
};