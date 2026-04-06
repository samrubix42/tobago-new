<?php

use Livewire\Component;

new class extends Component
{
    public string $title = 'Dashboard';

    public function mount(string $title = 'Dashboard'): void
    {
        $this->title = $title;
    }
};
