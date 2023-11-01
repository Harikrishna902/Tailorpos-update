<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Offer;
use Livewire\Component;

class Offers extends Component
{
    public $offers;
    public function render()
    {
        return view('livewire.frontend.offers')->layout('layouts.frontend');
    }

    //load products,sliders
    public function mount()
    {
        $this->offers = Offer::whereIsActive(1)->get();
    }
}
