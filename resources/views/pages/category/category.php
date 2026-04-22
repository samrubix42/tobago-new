<?php

use Livewire\Component;
use App\Models\Category;

new class extends Component
{
    public $categories;

    public function mount()
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
};