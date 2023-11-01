<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Auth;

class Logout extends Component
{
    // render the page
    public function render()
    {
        return view('livewire.admin.logout');
    }

    //logout user
    public function mount()
    {
        Auth::logout();
        return redirect('/admin');
    }
}