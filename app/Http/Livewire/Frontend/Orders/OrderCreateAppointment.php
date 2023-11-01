<?php

namespace App\Http\Livewire\Frontend\Orders;

use Livewire\Component;

class OrderCreateAppointment extends Component
{
    public function render()
    {
        return view('livewire.frontend.orders.order-create-appointment')->layout('layouts.frontend');
    }
}
