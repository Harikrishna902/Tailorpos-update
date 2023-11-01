<?php

namespace App\Http\Livewire\Branch\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Settings extends Component
{
    public $user,$name,$phone,$email,$address,$password;
    public function render()
    {
        return view('livewire.branch.settings.settings');
    }

    //load branch settings
    public function mount()
    {
        $this->user = User::find(Auth::user()->id);
        $this->name = $this->user->name;
        $this->phone =$this->user->phone;
        $this->email =$this->user->email;
        $this->address =$this->user->address;
        if(session()->has('selected_language'))
        {   /*if session has selected language */
            $this->lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = \App\Models\Translation::where('default',1)->first();
        }
    }

    //save branch settings
    public function save()
    {
        $idd = $this->user->id;
        $this->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$idd,
            'address' => 'required'
        ]);
        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->phone = $this->phone;
        $this->user->address = $this->address;
        if($this->password!="") {
            $this->user->password = Hash::make($this->password);
        }
        $this->user->save();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Settings Updated Successfully!']);
    }
}