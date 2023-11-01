<?php

namespace App\Http\Livewire\Admin\Stock;

use App\Models\Material;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentDetail;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockAdjustmentEdit extends Component
{
    public $material_query='',$materials,$cartItems=[],$inputi,$type = [],$qty = [],$date,$stockadjust;

    //render the page
    public function render()
    {
        return view('livewire.admin.stock.stock-adjustment-edit');
    }

    //set details from editing stock adjustment
    public function mount($id)
    {
        $item = StockAdjustment::findorfail($id);
        $this->stockadjust = $item;
        foreach($item->items as $row)
        {
            $data = collect([
                'name'  => $row->material_name,
                'id'    => $row->material_id,
                'unit'  => $row->unit,
            ]);
            $this->cartItems[$row->material_id] = $data;
            $this->qty[$row->material_id] = $row->quantity;
            $this->type[$row->material_id] = $row->type;
        }
        $this->date = Carbon::parse($item->date)->toDateString();
    }

    //global index, keeps track of a unique index
    public function addLocal($i)
    {
        $this->inputi = $i + 1;
    }

    //change materials list when updating material search
    public function updatedMaterialquery($value)
    {
        if($value != '')
        {
            $this->materials = Material::latest()->where('name','like','%'.$value.'%')->where('is_active',1)->limit(8)->get();
        }
        else{
            $this->materials = null;
        }
    }

    //select a material and add it to the cart
    public function selectMaterial($id)
    {
        $this->materials = null;
        $this->material_query = '';
        $material = Material::where('id',$id)->first();
        $this->addLocal($this->inputi);
        if(isset($this->cartItems[$id]))
        {
            $this->qty[$id] ++;
            return 1;
        }
        $data = collect([
            'name'  => $material->name,
            'id'    => $material->id,
            'unit'  => $material->unit,
        ]);
        $this->cartItems[$id] = $data;
        $this->qty[$id] = 1;
        $this->type[$id] = 1;
    }

    //reset empty string inputs to null
    public function updated($name,$value)
    {
        if ( $value == '' ) data_set($this, $name, null);
    }

    //save stock adjustment
    public function save()
    {
        if(!$this->cartItems || count($this->cartItems) == 0)
        {
            $this->dispatchBrowserEvent('alert',['type' => 'error','title' => 'Error!','message' => 'No Items are selected!']);
            return 1;
        }
        $isoutofstock = false;
        $notenoughstock = false;
        foreach($this->cartItems as $key => $row)
        {
            if($notenoughstock == true || $isoutofstock == true)
            {
                break;
            }
            $openstock = \App\Models\Material::where('id',$row['id'])->first()->opening_stock ?? 0;
            $iteminpurchase = \App\Models\PurchaseDetail::where('material_id',$row['id'])->sum('purchase_quantity');
            $iteminbills = \App\Models\InvoiceDetail::where('type',2)->where('item_id',$row['id'])->sum('quantity');
            $iteminadjustadd = \App\Models\StockAdjustmentDetail::where('type',2)->where('id','!=',$this->stockadjust->id)->sum('quantity');
            $iteminadjustsub = \App\Models\StockAdjustmentDetail::where('type',1)->where('id','!=',$this->stockadjust->id)->sum('quantity');
            $salesreturn = \App\Models\SalesReturnDetail::where('item_id',$row['id'])->whereType(2)->sum('quantity');
            $itemtotalquantity = ($iteminpurchase + $iteminadjustadd + $salesreturn + $openstock) - ($iteminbills + $iteminadjustsub);
            if($itemtotalquantity <= 0 && $this->type[$key] == 1)
            {
                $isoutofstock=true;
            }
            if($this->type[$key] == 1 && $itemtotalquantity > 0)
            {
                if($this->qty[$key] > $itemtotalquantity)
                {
                    $notenoughstock = true;
                }
            }
        }
        if($isoutofstock || $notenoughstock)
        {
            $this->dispatchBrowserEvent('alert',['type' => 'error','title' => 'Error!','message' => 'Some Items are out of stock!!']);
            return 1;
        }
        $this->validate([
            'qty.*' => 'numeric|required',   
        ]);
        $total = 0;
        foreach($this->qty as $row => $value)
        {
            $total ++;
        }
        $this->stockadjust->total_items = $total;
        $this->stockadjust->date = Carbon::parse($this->date);
        $this->stockadjust->save();
        StockAdjustmentDetail::where('stock_adjustment_id',$this->stockadjust->id)->delete();
        foreach($this->cartItems as $key => $row)
        {
            StockAdjustmentDetail::create([
                'stock_adjustment_id'   => $this->stockadjust->id,
                'material_id'   => $row['id'],
                'material_name' => $row['name'],
                'quantity'  => $this->qty[$key],
                'type'  => $this->type[$key],
                'unit'  => $row['unit']
            ]);
        }
        $this->emit('savemessage',['type' => 'success','title' => 'Success!','message' => 'Stock Adjustment Updated']);
        return redirect()->route('admin.stock_adjustments');
    }

    //remove row
    public function remove($id)
    {
        unset($this->cartItems[$id]);
        unset($this->qty[$id]);
        unset($this->type[$id]);
    }
}