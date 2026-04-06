<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function mount(): void
    {
        if (!Auth::check()) {
            return;
        }

        if (Auth::user()->is_admin) {
            $this->redirect(route('admin.dashboard'), navigate: true);
            return;
        }

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
            'is_admin' => true,
        ], $this->remember)) {
            $this->addError('email', 'Invalid admin credentials');
            return;
        }

        session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }
};

