<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Translation;

class LedgerComponent extends Component
{
    // render the page
    public function render()
    {
        return view('livewire.components.ledger-component');
    }
}