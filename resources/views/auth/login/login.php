<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::public-auth')] class extends Component
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

        $this->redirect(route('home'), navigate: true);
    }

    public function login()
    {
        $sessionId = session()->getId();

        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {

            $this->addError('email', 'Invalid credentials');
            return;
        }

        merge_guest_cart_for_user((int) Auth::id(), $sessionId);

        session()->regenerate();

        if (Auth::user()->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }
};
