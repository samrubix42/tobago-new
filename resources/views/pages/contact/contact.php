<?php

use App\Models\Contact;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required' => 'Please tell us your name.',
            'message.required' => 'Please share how we can help.',
            'message.min' => 'Please add a little more detail so we can help properly.',
        ]);

        Contact::create([
            ...$validated,
            'ip_address' => request()->ip(),
        ]);

        $this->reset(['name', 'email', 'phone', 'message']);

        $this->dispatch('toast-show', [
            'message' => 'Thanks for reaching out. Our team will get back to you shortly.',
            'type' => 'success',
        ]);
    }
};
