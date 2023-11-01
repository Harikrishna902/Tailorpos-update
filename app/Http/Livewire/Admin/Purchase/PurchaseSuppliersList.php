<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;

class PurchaseSuppliersList extends Component
{
    //render the page
    public function render()
    {
        return view('livewire.admin.purchase.purchase-suppliers-list');
    }
    // process on mount
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