<?php

namespace App\Http\Livewire\Admin\Customers;

use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Translation;

class CustomerGroups extends Component
{
    public $customer_groups,$name,$is_active=1,$customer_group,$search_query;

    //Render the page
    public function render()
    {
        $query = CustomerGroup::latest();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query->where('created_by',Auth::user()->id);
        }
        if($this->search_query != '')
        {
            $query->where('name','like','%'.$this->search_query.'%');
        }
        $this->customer_groups= $query->get();
        return view('livewire.admin.customers.customer-groups');
    }

    //Create customer group
    public function create()
    {
        $this->validate([
            'name'  => 'required'
        ]);
        CustomerGroup::create([
            'name'  => $this->name,
            'is_active' => $this->is_active,
            'created_by' => Auth::user()->id,
        ]);
        $this->resetFields();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->customer_groups = CustomerGroup::where('created_by',Auth::user()->id)->latest()->get();
        }
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->customer_groups = CustomerGroup::latest()->get();
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Customer Group has been created!']);
        $this->emit('closemodal');
    }
    /* set content to edit */   
    public function edit($id)
    {
        $this->customer_group = CustomerGroup::where('id',$id)->first();
        $this->is_active = $this->customer_group->is_active;
        $this->name = $this->customer_group->name;
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->customer_groups = CustomerGroup::where('created_by',Auth::user()->id)->latest()->get();
        }
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->customer_groups = CustomerGroup::latest()->get();
        }
        $this->resetErrorBag();
    }
    /* update the servicetype */
    public function update()
    {   /* if service type is exist */
        $this->validate([
            'name'  => 'required'
        ]);
        if($this->customer_group)
        {
            $this->customer_group->name = $this->name;
            $this->customer_group->is_active = $this->is_active;
            $this->customer_group->save();
        }
        $this->resetFields();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Customer Group has been updated!']);
        $this->emit('closemodal');
    }

    //reset input fields
    public function resetFields()
    {
        $this->name = null;
        $this->is_active = 1;
        $this->resetErrorBag();
    }

    //customer group toggle active
    public function toggle($id)
    {
        $group = CustomerGroup::find($id);
        if($group->is_active == 1)
        {
            $group->is_active = 0;
        }
        else{
            $group->is_active = 1;
        }
        $group->save();
    }
}