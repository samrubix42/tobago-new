
<div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl border border-slate-100">
    <h2 class="text-lg font-semibold text-slate-900 mb-4">Edit Blog</h2>

    <div class="space-y-4">
        <div>
            <label class="text-xs font-medium text-gray-600">Title</label>
            <input wire:model.live="title" placeholder="Post title" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
            @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Slug</label>
            <input wire:model.live="slug" placeholder="post-slug" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
            @error('slug')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Category</label>
            <select wire:model.live="category_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
                <option value="">Select category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Content</label>
            <div wire:ignore x-data="tinymceLivewire({ model: 'content' })" x-init="init()">
                <textarea
                    id="blog-update-content"
                    x-ref="textarea"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900"
                >{{ $content }}</textarea>
            </div>
            @error('content')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            <label class="text-xs font-medium text-gray-600">Featured Image</label>
            <input type="file" wire:model="featured_image" accept="image/*" class="text-sm text-gray-900">
            @if($existing_image)
                <img src="{{ asset('storage/' . $existing_image) }}" class="h-16 rounded-md object-cover">
            @endif
        </div>

        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" wire:model.live="is_published" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Published
            </label>
        </div>

        <div class="flex justify-end">
            <button wire:click="save" class="px-4 py-2 rounded-md bg-blue-600 text-white">Save</button>
        </div>
    </div>

    @include('components.tinymce-livewire')
</div>
