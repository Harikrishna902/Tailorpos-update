<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Auth;
use App\Models\Supplier;
use App\Models\Translation;

class EditSupplier extends Component
{
    public $supplier,$supplier_id,$name,$phone,$email,$tax,$address,$is_active=1,$opening_balance;
    public function render()
    {
        return view('livewire.components.edit-supplier');
    }

    //load supplier
    public function mount($id){
        /* if the user is not admin */
        if(Auth::user()->user_type != 2)
        {
            abort(404);
        }
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->supplier=Supplier::find($id);
       }
       if(!$this->supplier)
        {
            abort(404);
        }
        $this->supplier_id = $id;
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

    //update the supplier
    public function update()
    {
        $this->validate([
            'name'  => 'required',
            'phone'  => 'required',
            'email'  => 'nullable|email|unique:suppliers,email,'.$this->supplier->id,
           
        ]);
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
            $this->emit('resetPage');
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Supplier has been updated!']);
        }
    }
}