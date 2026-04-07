<?php

use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public ?int $testimonialId = null;
    public ?int $deleteId = null;

    public string $name = '';
    public ?string $city = null;
    public string $review = '';
    public int $stars = 5;
    public bool $status = true;

    #[On('refresh-testimonial-list')]
    public function mount(): void
    {
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->testimonialId = null;
        $this->name = '';
        $this->city = null;
        $this->review = '';
        $this->stars = 5;
        $this->status = true;
        $this->deleteId = null;
    }

    public function openEditModal(int $id): void
    {
        $t = Testimonial::findOrFail($id);

        $this->resetValidation();
        $this->testimonialId = $t->id;
        $this->name = $t->name;
        $this->city = $t->city;
        $this->review = $t->review;
        $this->stars = (int) $t->stars;
        $this->status = (bool) $t->is_active;
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'review' => ['required', 'string'],
            'stars' => ['required', 'integer', 'between:1,5'],
        ]);

        $testimonial = $this->testimonialId ? Testimonial::findOrFail($this->testimonialId) : new Testimonial();

        $testimonial->fill([
            'name' => $validated['name'],
            'city' => $validated['city'],
            'review' => $validated['review'],
            'stars' => $validated['stars'],
            'is_active' => $this->status,
        ]);

        if (! $testimonial->exists) {
            $testimonial->sort_order = (int) Testimonial::max('sort_order') + 1;
        }

        $testimonial->save();

        $this->dispatch('toast-show', [
            'message' => 'Testimonial saved successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('refresh-testimonial-list');
        $this->resetForm();
    }

    public function delete(?int $id = null): void
    {
        $id = $id ?? $this->deleteId;

        if (! $id) {
            return;
        }

        $t = Testimonial::findOrFail($id);
        $t->delete();

        $this->dispatch('toast-show', [
            'message' => 'Testimonial deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('refresh-testimonial-list');
        $this->dispatch('close-delete-modal');
        $this->deleteId = null;

        // Ensure pagination resets when current page might be past last page
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Testimonial::query()
            ->when($this->search !== '', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('sort_order')
            ->orderBy('name');

        return view('admin.testimonial-list.testimonial-list', [
            'testimonials' => $query->paginate($this->perPage),
        ]);
    }

    public function handleTestimonialSort($item, $position): void
    {
        $t = Testimonial::find($item);

        if (! $t) {
            return;
        }

        $t->update(['sort_order' => (int) $position]);

        $this->dispatch('refresh-testimonial-list');

        $this->dispatch('toast-show', [
            'message' => 'Testimonial order updated successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

};
