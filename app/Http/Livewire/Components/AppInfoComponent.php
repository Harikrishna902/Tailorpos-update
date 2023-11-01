<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Translation;

class AppInfoComponent extends Component
{
    // render the page
    public function render()
    {
        return view('livewire.components.app-info-component');
    }
}