<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use Auth;
use App\Models\Translation;

class Purchases extends Component
{
    public $search,$purchases;

    //render the page and load purchases
    public function render()
    {
        $query = Purchase::where('created_by',Auth::user()->id)->latest();
        if($this->search != '')
        {
            $value = $this->search;
            $query->whereHas('supplier', function($query1) use($value){
                $query1->where('name', 'LIKE', '%'. $value .'%')
                ->orWhere('tax_number', 'like', '%' . $value . '%');                ;
            });
        }
        $this->purchases = $query->get();
        return view('livewire.admin.purchase.purchases');
    }
}