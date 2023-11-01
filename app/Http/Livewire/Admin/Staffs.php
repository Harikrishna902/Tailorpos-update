<?php

namespace App\Http\Livewire\Admin;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Staffs extends Component
{
    public $search='',$name,$role=1,$phone,$email,$password,$branches,$branch=0,$staff,$staffs,$is_active = 1;

    //render the page and list staff
    public function render()
    {
        /* if the user is admin */
        if(Auth::user()->user_type==2){
            abort(404);
        }
        if(Auth::user()->user_type==2) {
            $query = User::whereIn('user_type',[4,5,6])->latest();
        }
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query = User::whereIn('user_type',[4,5,6])->where('created_by',Auth::user()->id)->latest();
        }
        if($this->search != '')
        {
            $query->where('name','like','%'.$this->search.'%');
        }
        $this->staffs = $query->get();
        return view('livewire.admin.staffs');
    }

    //list branches and select user's own branch as default
    public function mount()
    {
        $this->branches = User::where('user_type',3)->where('is_active',1)->get();
        $this->branch = Auth::user()->id;
        
    }

    //create branch
    public function create()
    {
        $this->validate([
            'name'  => 'required',
            'phone'  => 'required',
            'email' => 'required|unique:users,email|email',
            'password'  => 'required',
            'role'  => 'required'
        ]);
        $user = User::create([
            'name'  => $this->name,
            'user_type' => $this->role,
            'phone' => $this->phone,
            'email' => $this->email,
            'password'  => Hash::make($this->password),
            'branch_id'  => Auth::user()->id,
            'is_active' => $this->is_active,
            'created_by' => Auth::user()->id,
        ]);
        $this->resetFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Staff has been created!']);
    }

    //reset input fields
    public function resetFields()
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->resetErrorBag();
    }

    //prepare for edit
    public function edit($id)
    {
        $this->resetErrorBag();
        $this->staff = User::find($id);
        $this->name = $this->staff->name;
        $this->phone =$this->staff->phone;
        $this->email =$this->staff->email;
        $this->is_active = $this->staff->is_active;
        $this->role = $this->staff->user_type;
    }

    //edit staff
    public function update()
    {
        $this->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,'.$this->staff->id,
            'phone' => 'required',
            'role'  => 'required'
        ]);
        $this->staff->name = $this->name;
        $this->staff->phone = $this->phone;
        $this->staff->email = $this->email;
        $this->staff->user_type = $this->role;
        $this->staff->is_active = $this->is_active;
        if($this->password)
        {
            $this->staff->password = Hash::make($this->password);
        }
        $this->staff->save();
        $this->resetFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Staff has been updated!']);
    }

    //delete staff
    public function delete($id)
    {
        $this->staff = User::find($id);
        $this->staff->delete();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Staff Deleted Successfully!']);
        $this->staff = null;
    }

    //toggle staff active status
    public function toggle($id)
    {
        $staff = User::find($id);
        if($staff->is_active == 1)
        {
            $staff->is_active = 0;
        }
        else{
            $staff->is_active = 1;
        }
        $staff->save();
    }
}