<?php

namespace App\Http\Livewire\Admin;

use App\Models\Translation;
use Livewire\Component;
use App\Models\User;
use Hash;
use Auth;

class Branches extends Component
{

    public $editMode = false;
    public $name, $email, $password, $phone, $user, $users, $address, $is_active = 1, $search;

    //render the page & list branch
    public function render()
    {
        if (Auth::user()->user_type != 2) {
            abort(404);
        }
        $query = User::where('user_type', 3)->latest();
        if ($this->search != '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $this->users = $query->get();
        return view('livewire.admin.branches');
    }

    //validation rules
    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => "required",
        'phone' => 'nullable|numeric',
        'address' => 'required'
    ];

    //Save data when user clicks save button
    public function save()
    {
        //If the user did not click on the edit button
        if ($this->editMode == false) {
            $this->validate();
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone' => $this->phone,
                'address' => $this->address,
                'user_type' => 3,
                'is_active' => $this->is_active ?? 0,
                'created_by' => Auth::user()->id,
            ]);
            $this->users = User::where('user_type', 3)->latest()->get();
            if ($user) {
                $this->resetInputFields();
                $this->emit('closemodal');
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'success',  'message' => 'Branch Created Successfully!']
                );
            }
        }
        //If user clicked on the edit button
        else if ($this->editMode == true && $this->user) {
            $idd = $this->user->id;
            $this->validate([
                'name' => 'required',
                'email' => 'required|unique:users,email,' . $idd,
                'address' => 'required'
            ]);
            $this->user->name = $this->name;
            $this->user->email = $this->email;
            $this->user->phone = $this->phone;
            $this->user->address = $this->address;
            $this->user->is_active = $this->is_active ?? 0;
            if ($this->password != "") {
                $this->user->password = Hash::make($this->password);
            }
            $this->user->save();
            $this->editMode = false;
            $this->users = User::where('user_type', 3)->latest()->get();
        }
        $this->resetInputFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success',  'message' => 'Branch Updated Successfully!']
        );
    }
    //Reset Input Fields
    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->phone = '';
        $this->address = '';
        $this->is_active = 1;
        $this->resetErrorBag();
        $this->editMode = false;
        $this->user = null;
    }
    //If user clicked on the edit button get item id and initialize input variables with it.
    public function edit($id)
    {
        $this->editMode = true;
        $this->resetErrorBag();
        $this->user = User::find($id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->is_active = $this->user->is_active;
        $this->address = $this->user->address;
    }

    //toggle user active status
    public function toggle($id)
    {
        $user = User::find($id);
        $user->is_active = !($user->is_active);
        $user->save();
    }

    // process on mount 
    public function mount()
    {
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
    }
}