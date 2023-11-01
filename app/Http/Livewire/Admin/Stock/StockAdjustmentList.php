<?php

namespace App\Http\Livewire\Admin\Stock;

use App\Models\StockAdjustment;
use App\Models\StockAdjustmentDetail;
use App\Models\Translation;
use Livewire\Component;

class StockAdjustmentList extends Component
{
    public $stockadjustments,$search,$stockadjust;

    //render the page
    public function render()
    {
        $query = StockAdjustment::latest();
        if($this->search != '')
        {
            $query->where('date','like','%'.$this->search.'%');
            $search = $this->search;
            $query->orwherehas('createdBy',function($query2) use ($search)
            {
                $query2->where('name','like',$search.'%');
            });
            $query->orwherehas('items',function($query2) use ($search)
            {
                $query2->where('material_name','like',$search.'%');
            });
        }
        $this->stockadjustments = $query->get();
        return view('livewire.admin.stock.stock-adjustment-list');
    }

    //confirm stock adjustment delete
    public function deleteConfirm($id)
    {
        $this->stockadjust = StockAdjustment::find($id);
    }

    //delete stock adjustment
    public function delete()
    {
        $this->stockadjust->delete();
        StockAdjustmentDetail::where('stock_adjustment_id',$this->stockadjust->id)->delete();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert',['type' => 'error','success' => 'Success!','message' => 'Stock Adjustment has been deleted']);
    }
}