<?php

use App\Models\Contact;
use Livewire\Component;

new class extends Component
{
    public string $title = 'Contact Us | Tobac-Go Hookah Store Noida';

    public string $metaDescription = 'Contact Tobac-Go, the leading premium hookah and accessories store. Get store directions in Sector 76 Noida, call us at +91 78384 49604, or message us on WhatsApp.';

    public string $metaKeywords = 'contact tobac-go, hookah shop noida, hookah store noida, tobac-go phone number, whatsapp support hookah, buy hookah online';

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
