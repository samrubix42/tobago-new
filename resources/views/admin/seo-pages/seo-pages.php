<?php

use App\Models\SeoContent;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    // Form fields
    public ?int $selectedId = null;
    public string $name = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $meta_keywords = '';
    public string $page_slug = '';

    protected array $rules = [
        'name' => 'required|string|max:255',
        'meta_title' => 'required|string|max:255',
        'meta_description' => 'required|string',
        'meta_keywords' => 'required|string',
        'page_slug' => 'required|string|max:255',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->selectedId = null;
        $this->name = '';
        $this->meta_title = '';
        $this->meta_description = '';
        $this->meta_keywords = '';
        $this->page_slug = '';
        $this->resetErrorBag();
    }

    public function openEditModal(int $id): void
    {
        $this->resetForm();
        $seo = SeoContent::findOrFail($id);
        $this->selectedId = $seo->id;
        $this->name = $seo->name;
        $this->meta_title = $seo->meta_title;
        $this->meta_description = $seo->meta_description;
        $this->meta_keywords = $seo->meta_keywords;
        $this->page_slug = $seo->page_slug;
    }

    public function save(): void
    {
        // Require unique page_slug unless it belongs to the currently edited item
        $slugRule = 'unique:seo_contents,page_slug';
        if ($this->selectedId) {
            $slugRule .= ',' . $this->selectedId;
        }

        $this->validate(array_merge($this->rules, [
            'page_slug' => ['required', 'string', 'max:255', $slugRule],
        ]));

        SeoContent::updateOrCreate(
            ['id' => $this->selectedId],
            [
                'name' => $this->name,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'page_slug' => $this->page_slug,
            ]
        );

        $this->dispatch('close-modal');
        $this->dispatch('toast-show', [
            'message' => $this->selectedId ? 'SEO Content updated successfully.' : 'SEO Content created successfully.',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
    }

    public function delete(): void
    {
        if ($this->selectedId) {
            SeoContent::destroy($this->selectedId);
            $this->dispatch('close-delete-modal');
            $this->dispatch('toast-show', [
                'message' => 'SEO Content deleted successfully.',
                'type' => 'success',
                'position' => 'top-right',
            ]);
            $this->resetForm();
        }
    }

    public function render()
    {
        $seoPages = SeoContent::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('page_slug', 'like', '%' . $this->search . '%')
                    ->orWhere('meta_title', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('admin.seo-pages.seo-pages', [
            'seoPages' => $seoPages,
        ])->layout('layouts.admin', ['title' => 'SEO Pages Management']);
    }
};