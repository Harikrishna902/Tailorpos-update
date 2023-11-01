<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;
use Auth;
use App\Models\Supplier;

class PurchaseSuppliersView extends Component
{
    public $supplier_id,$supplier;

    //render the page
    public function render()
    {
        return view('livewire.admin.purchase.purchase-suppliers-view');
    }
    // process on mount
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
        if(session()->has('selected_language'))
        {   /*if session has selected language */
            $this->lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = \App\Models\Translation::where('default',1)->first();
        }
        $this->supplier_id = $id;
    }
}