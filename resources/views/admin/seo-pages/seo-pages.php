<?php

use App\Models\SeoContent;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    // Form fields
    public ?int $selectedId = null;
    public ?string $name = null;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;
    public ?string $page_slug = null;
    public ?string $content = null;

    protected array $rules = [
        'name' => 'nullable|string|max:255',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'page_slug' => 'nullable|string|max:255',
        'content' => 'nullable|string',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->selectedId = null;
        $this->name = null;
        $this->meta_title = null;
        $this->meta_description = null;
        $this->meta_keywords = null;
        $this->page_slug = null;
        $this->content = null;
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
        $this->content = $seo->content ?? '';
    }

    public function save(): void
    {
        $this->normalizeNullableFields();

        $this->validate(array_merge($this->rules, [
            'page_slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('seo_contents', 'page_slug')->ignore($this->selectedId),
            ],
        ]));

        SeoContent::updateOrCreate(
            ['id' => $this->selectedId],
            [
                'name' => $this->name,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'page_slug' => $this->page_slug,
                'content' => $this->content,
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

    private function normalizeNullableFields(): void
    {
        foreach (['name', 'meta_title', 'meta_description', 'meta_keywords', 'page_slug', 'content'] as $field) {
            $this->{$field} = blank($this->{$field}) ? null : trim($this->{$field});
        }
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
