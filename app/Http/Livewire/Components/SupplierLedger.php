<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Auth;
use App\Models\Supplier;
use App\Models\Translation;

class SupplierLedger extends Component
{
    public $supplier_id,$supplier,$is_active;
    protected $listeners=['resetPage' => 'reloadItems'];
    // render the page
    public function render()
    {
        return view('livewire.components.supplier-ledger');
    }

    // process on mount
    public function mount($id){
       // if the user is not admin 
        if(Auth::user()->user_type != 2)
        {
            abort(404);
        }
        // if the user is admin
        if(Auth::user()->user_type==2) {
            $this->supplier=Supplier::find($id);
       }
       if(!$this->supplier)
        {
            abort(404);
        }
        $this->supplier_id = $id;
    }

    //reload supplier
    public function reloadItems()
    {
        $this->supplier->refresh();
    }
    
    //toggle supplier is active status
    public function toggle($id)
    {
        $user = Supplier::find($id);
        if($user->is_active == 1)
        {
            $user->is_active = 0;
        }
        else{
            $user->is_active = 1;
        }
        $user->save();
        $this->supplier=Supplier::find($id);
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Status changed Successfully!']);
    }
}