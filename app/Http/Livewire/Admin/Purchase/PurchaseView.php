<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;
use Auth;
use App\Models\Purchase;
use App\Models\Translation;
class PurchaseView extends Component
{
    public $purchase,$current_id;

    //render the page
    public function render()
    {
        return view('livewire.admin.purchase.purchase-view');
    }

    //load purchase
    public function mount($id){
        
        $this->purchase = Purchase::where('created_by',Auth::user()->id)->where('id',$id)->first();
        if(!($this->purchase)){
            abort(404);
        }
        $this->current_id = $id;
    }

    //change status of purchase
    public function changeStatus($id){
        $purchase = Purchase::find($id);
        $purchase->purchase_type = 2;
        $purchase->save();
        $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Purchase Saved As Pushed successfully!']);
        return redirect()->route('admin.purchases');
    }
}