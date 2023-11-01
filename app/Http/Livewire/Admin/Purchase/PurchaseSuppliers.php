<?php

namespace App\Http\Livewire\Admin\Purchase;

use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchaseSuppliers extends Component
{
    public $suppliers,$supplier,$name,$phone,$email,$tax,$address,$is_active=1,$opening_balance,$search='';

    //render the page and load suppliers
    public function render()
    {
        $query = Supplier::latest();
        if($this->search != '')
        {
            $query->where('name','like','%'.$this->search.'%');
        }
        $this->suppliers = $query->get();
        return view('livewire.admin.purchase.purchase-suppliers');
    }

    //create supplier
    public function create()
    {
        $this->validate([
            'name'  => 'required',
            'phone'  => 'required',
            'email'  => 'nullable|email|unique:suppliers,email'
        ]);
        if($this->opening_balance == '' || $this->opening_balance == null)
        {
            $this->opening_balance = 0;
        }
        if($this->email == '')
        {
            $this->email = null;
        }
        Supplier::create([
            'name'  => $this->name,
            'phone' => $this->phone,
            'email'  => $this->email ?? null,
            'tax_number' => $this->tax,
            'supplier_address'  => $this->address,
            'is_active' => $this->is_active,
            'opening_balance' => $this->opening_balance,
            'created_by'    => Auth::user()->id
        ]);
        $this->resetFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Supplier has been created!']);
    }

    //prepare supplier edit
    public function edit($id)
    {
        $this->resetErrorBag();
        $this->supplier = Supplier::find($id);
        if($this->supplier)
        {
            $this->name = $this->supplier->name;
            $this->phone = $this->supplier->phone;
            $this->email = $this->supplier->email;
            $this->tax = $this->supplier->tax_number;
            $this->address = $this->supplier->supplier_address;
            $this->is_active = $this->supplier->is_active;
            $this->opening_balance = $this->supplier->opening_balance;
        }
    }

    //update supplier
    public function update()
    {
        $this->validate([
            'name'  => 'required',
            'phone'  => 'required',
            'email'  => 'nullable|email|unique:suppliers,email,'.$this->supplier->id,
           
        ]);
        if($this->opening_balance == '' || $this->opening_balance == null)
        {
            $this->opening_balance = 0;
        }
        if($this->email == '')
        {
            $this->email = null;
        }
        if($this->supplier)
        {
            $this->supplier->name = $this->name;
            $this->supplier->phone = $this->phone ;
            $this->supplier->email = $this->email;
            $this->supplier->tax_number = $this->tax;
            $this->supplier->supplier_address = $this->address;
            $this->supplier->is_active = $this->is_active ;
            $this->supplier->opening_balance = $this->opening_balance ;
            $this->supplier->save();
            $this->resetFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Supplier has been updated!']);
        }
    }

    //reset all input fields
    public function resetFields()
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->tax = '';
        $this->address = '';
        $this->is_active = 1;
        $this->opening_balance = '';
        $this->resetErrorBag();
    }

    //toggle supplier active status
    public function toggle($id)
    {
        $supplier = Supplier::find($id);
        if($supplier->is_active == 1)
        {
            $supplier->is_active = 0;
        }
        else{
            $supplier->is_active = 1;
        }
        $supplier->save();
    }

    /* process on mount */
    public function mount()
    {
        if(session()->has('selected_language'))
        {   /*if session has selected language */
            $this->lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = \App\Models\Translation::where('default',1)->first();
        }
    }
}