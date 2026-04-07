<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public ?int $deleteId = null;

    #[On('refresh-blog-list')]
    public function mount(): void
    {
        $this->resetValidation();
        $this->deleteId = null;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(?int $id = null): void
    {
        $id = $id ?? $this->deleteId;

        if (! $id) {
            return;
        }

        $blog = Blog::findOrFail($id);
        if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        $blog->delete();

        $this->dispatch('toast-show', [
            'message' => 'Blog deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-delete-modal');
        $this->deleteId = null;
        $this->resetPage();
        $this->dispatch('refresh-blog-list');
    }

    public function render()
    {
        $query = Blog::query()
            ->with(['category', 'author'])
            ->when($this->search !== '', function ($q) {
                $q->where(function ($nested) {
                    $nested->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%');
                });
            });

        return view('admin.blog.blog-list.blog-list', [
            'blogs' => $query->latest()->paginate($this->perPage),
        ]);
    }
};
