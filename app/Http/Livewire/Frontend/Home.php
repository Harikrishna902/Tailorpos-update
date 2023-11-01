<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Home extends Component
{
    public $featured_products,$products,$branch,$sliders,$cart;
    protected $listeners = ['branchChanged'];
    //render page
    public function render()
    {
        return view('livewire.frontend.home')->layout('layouts.frontend');
    }

    //load products,sliders
    public function mount()
    {
        $session_branch = Session::get('current_branch');
        if($session_branch)
        {
            $this->branch = User::whereIn('user_type',[2,3])->whereId($session_branch)->first();
            if(!$this->branch)
            {
                $this->branch = User::whereUserType(2)->first();
            }
        }
        else{
            $this->branch = User::whereUserType(2)->first();
            Session::put('current_branch',$this->branch->id);
        }
        $this->featured_products = Product::whereIsFeatured(1)->whereIsActive(1)->get();
        $this->products = Product::whereIsActive(1)->get();
        $this->sliders = Slider::whereIsActive(1)->get();

        if(Session::get('cart'))
        {
            $this->cart = Session::get('cart');
        }
    }

    public function branchChanged($e)
    {
    }

    public function addItemToCart($item)
    {
        
    }

    public function getProducts()
    {
        return json_encode($this->products);
    }
}