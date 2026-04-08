<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::admin')] class extends Component
{
    use WithFileUploads;

    public array $settings = [];
    public $logoFile = null;

    public function mount(): void
    {
        $this->settings = $this->defaultSettings();

        $storedSettings = Setting::query()
            ->pluck('value', 'key')
            ->toArray();

        foreach ($storedSettings as $key => $value) {
            if (array_key_exists($key, $this->settings)) {
                $this->settings[$key] = (string) $value;
            }
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'settings.delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'settings.free_delivery_amount' => ['nullable', 'numeric', 'min:0'],
            'logoFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'settings.project_name' => ['nullable', 'string', 'max:255'],
            'settings.phone_number' => ['nullable', 'string', 'max:50'],
            'settings.email' => ['nullable', 'email', 'max:255'],
            'settings.whatsapp_number' => ['nullable', 'string', 'max:50'],
            'settings.address' => ['nullable', 'string', 'max:500'],
            'settings.instagram_url' => ['nullable', 'url', 'max:255'],
            'settings.linkedin_url' => ['nullable', 'url', 'max:255'],
            'settings.facebook_url' => ['nullable', 'url', 'max:255'],
            'settings.x_url' => ['nullable', 'url', 'max:255'],
            'settings.application_name' => ['nullable', 'string', 'max:255'],
            'settings.footer_text' => ['nullable', 'string', 'max:500'],
        ]);

        if ($this->logoFile) {
            $oldLogo = $this->settings['logo'] ?? null;

            $newLogoPath = $this->logoFile->store('settings', 'public');
            $validated['settings']['logo'] = $newLogoPath;

            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
        }

        foreach ($validated['settings'] as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'group' => in_array($key, ['delivery_fee', 'free_delivery_amount'], true) ? 'order' : 'system',
                    'value' => $value,
                ]
            );

            $this->settings[$key] = (string) ($value ?? '');
        }

        $this->logoFile = null;

        $this->dispatch('toast-show', [
            'message' => 'Settings saved successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    protected function defaultSettings(): array
    {
        return app_setting_defaults();
    }
};