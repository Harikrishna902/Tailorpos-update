<?php

namespace App\Http\Livewire\Frontend;

use App\Models\OnlineCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Signup extends Component
{
    public $name,$password,$email,$phone,$terms_conditions=0;
    public function render()
    {
        return view('livewire.frontend.signup')->layout('layouts.frontend');
    }

    public function mount()
    {
        if(Auth::guard('customer')->user())
        {
            return redirect()->route('frontend');
        }
    }

    public function register()
    {
        $this->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:online_customers,email',
            'password'  => 'required',
            'phone' => 'required',
            'terms_conditions'  => 'accepted'
        ]);
        $user = new OnlineCustomer();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->phone = $this->phone;
        $user->save();
        Auth::guard('customer')->login($user);
        return redirect()->route('frontend');
    }
}