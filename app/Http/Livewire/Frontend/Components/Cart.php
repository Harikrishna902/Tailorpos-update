<?php

namespace App\Http\Livewire\Frontend\Components;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Cart extends Component
{
    public function render()
    {
        return view('livewire.frontend.components.cart');
    }


    public function save($items)
    {
        $realCart = [
            'subtotal'  => 0,
            'total' => 0,
            'tax_total' => 0,
            'tax_percentage'    => 0,
            'items' => []
        ];
        $realCart['items']  = $items;
        $cartArray = $realCart;
        Session::put('cart_array_items',$cartArray);
        return redirect()->route('frontend.place-order');
    }
}
