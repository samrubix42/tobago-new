<?php

use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::app')] #[Title('My Addresses')] class extends Component
{
    // Form fields
    public ?int $editingId = null;
    public bool $showForm  = false;

    public string $type           = 'home';
    public bool   $is_default     = false;
    public string $full_name      = '';
    public string $phone          = '';
    public string $alternate_phone = '';
    public string $address_line1  = '';
    public string $address_line2  = '';
    public string $landmark       = '';
    public string $city           = '';
    public string $state          = '';
    public string $country        = 'India';
    public string $pincode        = '';

    public function mount(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'), navigate: true);
        }
    }

    public function rules(): array
    {
        return [
            'type'            => ['required', 'in:home,work,other'],
            'full_name'       => ['required', 'string', 'max:255'],
            'phone'           => ['required', 'string', 'max:20'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'address_line1'   => ['required', 'string', 'max:255'],
            'address_line2'   => ['nullable', 'string', 'max:255'],
            'landmark'        => ['nullable', 'string', 'max:255'],
            'city'            => ['required', 'string', 'max:100'],
            'state'           => ['required', 'string', 'max:100'],
            'country'         => ['required', 'string', 'max:100'],
            'pincode'         => ['required', 'string', 'max:10'],
        ];
    }

    public function openForm(?int $addressId = null): void
    {
        $this->resetForm();
        $this->showForm = true;

        if ($addressId) {
            $address = UserAddress::where('user_id', Auth::id())->findOrFail($addressId);
            $this->editingId       = $address->id;
            $this->type            = $address->type;
            $this->is_default      = $address->is_default;
            $this->full_name       = $address->full_name;
            $this->phone           = $address->phone;
            $this->alternate_phone = $address->alternate_phone ?? '';
            $this->address_line1   = $address->address_line1;
            $this->address_line2   = $address->address_line2 ?? '';
            $this->landmark        = $address->landmark ?? '';
            $this->city            = $address->city;
            $this->state           = $address->state;
            $this->country         = $address->country;
            $this->pincode         = $address->pincode;
        }
    }

    public function save(): void
    {
        $this->validate();
        $user = Auth::user();

        // Set all others as non-default if this is set as default
        if ($this->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $data = [
            'type'            => $this->type,
            'is_default'      => $this->is_default,
            'full_name'       => $this->full_name,
            'phone'           => $this->phone,
            'alternate_phone' => $this->alternate_phone ?: null,
            'address_line1'   => $this->address_line1,
            'address_line2'   => $this->address_line2 ?: null,
            'landmark'        => $this->landmark ?: null,
            'city'            => $this->city,
            'state'           => $this->state,
            'country'         => $this->country,
            'pincode'         => $this->pincode,
        ];

        if ($this->editingId) {
            $user->addresses()->where('id', $this->editingId)->update($data);
        } else {
            // Auto-set default if first address
            if ($user->addresses()->count() === 0) {
                $data['is_default'] = true;
            }
            $user->addresses()->create($data);
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function setDefault(int $addressId): void
    {
        $user = Auth::user();
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->where('id', $addressId)->update(['is_default' => true]);
    }

    public function delete(int $addressId): void
    {
        Auth::user()->addresses()->where('id', $addressId)->delete();
    }

    private function resetForm(): void
    {
        $this->editingId       = null;
        $this->type            = 'home';
        $this->is_default      = false;
        $this->full_name       = '';
        $this->phone           = '';
        $this->alternate_phone = '';
        $this->address_line1   = '';
        $this->address_line2   = '';
        $this->landmark        = '';
        $this->city            = '';
        $this->state           = '';
        $this->country         = 'India';
        $this->pincode         = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('pages.user.address.address', [
            'addresses' => Auth::user()->addresses()->latest()->get(),
        ]);
    }
};