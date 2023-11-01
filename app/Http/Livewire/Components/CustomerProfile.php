<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Translation;

class CustomerProfile extends Component
{
    public $customer_id,$customer,$is_active;
    protected $listeners=['resetPage' => 'reloadItems'];

    //render the page
    public function render()
    {
        return view('livewire.components.customer-profile');
    }

    //set customer
    public function mount($id){
        
      $this->customer_id = $id;
      $this->customer = Customer::find($id);
    }
    
    //reload customer
    public function reloadItems()
    {
        $this->customer->refresh();
    }
    
    //toggle active status of customer
    public function toggle($id)
    {
        $user = Customer::find($id);
        if($user->is_active == 1)
        {
            $user->is_active = 0;
        }
        else{
            $user->is_active = 1;
        }
        $user->save();
        $this->customer=Customer::find($id);
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Status changed Successfully!']);
    }
}