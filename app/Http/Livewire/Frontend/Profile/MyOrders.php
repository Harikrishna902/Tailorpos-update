<?php

namespace App\Http\Livewire\Frontend\Profile;

use App\Models\OnlineOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyOrders extends Component
{
    public $active_orders,$completed_orders,$activeOrder;
    public function render()
    {
        $this->active_orders = OnlineOrder::whereCustomerId(Auth::guard('customer')->user()->id)->latest()->get();
        return view('livewire.frontend.profile.my-orders')->layout('layouts.frontend');
    }

    public function viewOrder($order)
    {
        $order = OnlineOrder::whereId($order)->with('details')->first();
        $this->activeOrder = $order;
    }
}