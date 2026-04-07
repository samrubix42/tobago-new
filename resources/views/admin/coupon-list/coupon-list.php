<?php

use App\Models\Coupon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public ?int $couponId = null;
    public ?int $deleteId = null;

    public string $code = '';
    public string $type = 'percentage';
    public string $value = '';
    public string $min_amount = '0';
    public bool $is_active = true;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->resetValidation();

        $this->couponId = null;
        $this->deleteId = null;

        $this->code = '';
        $this->type = 'percentage';
        $this->value = '';
        $this->min_amount = '0';
        $this->is_active = true;
    }

    public function openEditModal(int $couponId): void
    {
        $coupon = Coupon::findOrFail($couponId);

        $this->resetValidation();
        $this->couponId = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = (string) $coupon->value;
        $this->min_amount = (string) $coupon->min_amount;
        $this->is_active = (bool) $coupon->is_active;
    }

    public function confirmDelete(int $couponId): void
    {
        $this->deleteId = $couponId;
    }

    public function save(): void
    {
        $rules = [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($this->couponId),
            ],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ];

        if ($this->type === 'percentage') {
            $rules['value'][] = 'max:100';
        }

        $validated = $this->validate($rules);

        $coupon = $this->couponId ? Coupon::findOrFail($this->couponId) : new Coupon();

        $coupon->fill([
            'code' => strtoupper(trim($validated['code'])),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_amount' => $validated['min_amount'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $coupon->save();

        $this->dispatch('toast-show', [
            'message' => 'Coupon saved successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('refresh-coupon-list');
        $this->resetForm();
    }

    public function delete(?int $couponId = null): void
    {
        $id = $couponId ?? $this->deleteId;

        if (! $id) {
            return;
        }

        Coupon::query()->whereKey($id)->delete();

        $this->dispatch('toast-show', [
            'message' => 'Coupon deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('refresh-coupon-list');
        $this->dispatch('close-delete-modal');
        $this->deleteId = null;
    }

    #[On('refresh-coupon-list')]
    public function refreshList(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $coupons = Coupon::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($nested) {
                    $nested->where('code', 'like', '%' . $this->search . '%')
                        ->orWhere('type', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('admin.coupon-list.coupon-list', [
            'coupons' => $coupons,
        ]);
    }
};

