<?php

namespace App\Http\Livewire\Admin\Stock;

use Livewire\Component;
use App\Models\StockTransfer;
use App\Models\PurchaseDetail;
use App\Models\StockTransferDetail;
use App\Models\StockAdjustmentDetail;
use App\Models\User;
use App\Models\Translation;
use App\Models\Material;
use App\Models\StockAdjustment;
use App\Models\SalesReturnDetail;
use App\Models\InvoiceDetail;
use Auth;

class StockTransferAdd extends Component
{
    public $search = '';
    public $material_query, $material_results, $total_quantity = 0, $branch_id, $branches, $total_items = 0,$stock_transfer_id;
    public $inputs = [], $material_name = [], $material_unit = [], $quantity = [], $material, $materials, $material_code = [], $material_id = [];

    //render the page
    public function render()
    {
        return view('livewire.admin.stock.stock-transfer-add');
    }

    //set default date
    public function mount()
    {
        $this->branches = User::where('user_type', 3)->where('is_active', 1)->latest()->get();
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
        $this->date = \Carbon\Carbon::now()->toDateString();
    }

    /* update value while change input fields */
    public function updated($name, $value)
    {
        if ($name == "material_query" && $value != '') {
            $this->material_results = Material::where('created_by', Auth::user()->id)->where('name', 'like', $value . '%')->where('is_active', 1)->get();
        }
        if ($name == "material_query" && $value == '') {
            $this->material_results = '';
        }
        if ($name == "branch_id" && $value != '') {
            $this->branch_id = $value;
        }
        if ($name == "branch_id" && $value == '') {
            $this->branch_id = '';
        }
    }

    /* select the material */
    public function selectMaterial($id)
    {
        $this->material_results = '';
        $this->material_query = '';
        $available = $this->availablility($id);
        if ($available <= 0) {
            $this->dispatchBrowserEvent('swal', ['title' => 'Material Stock Not Available!']);
            return 1;
        } else {
            $this->selectedMaterial($id);
        }
    }

    /* set the select the material */
    public function selectedMaterial($item)
    {
        if ($item) {
            $base_material = Material::where('id', $item)->first();
            if ($base_material) {
                if (!isset($this->material_name[$base_material->id])) {
                    $this->material_id[$base_material->id] = $base_material->id;
                    $this->material_name[$base_material->id] = $base_material->name;
                    $this->material_unit[$base_material->id] = $base_material->unit;
                    $available = $this->availablility($base_material->id);
                    if ($available < 1) {
                        $this->quantity[$base_material->id] = number_format($available, 2);
                    } else {
                        $this->quantity[$base_material->id] = 1;
                    }
                }
                $this->add($base_material->id);
            }
        }
    }

    public function finalCalculation()
    {
        $this->total_quantity = 0;
        foreach ($this->inputs as $key => $value) {
            $this->total_quantity = $this->total_quantity +  $this->quantity[$key];
        }
    }

    /* delete the material row */
    public function delete($id)
    {
        if (isset($this->inputs[$id])) {
            unset($this->inputs[$id]);
            unset($this->material_name[$id]);
            unset($this->material_code[$id]);
            unset($this->quantity[$id]);
        }
        $this->total_items = $this->total_items - 1;
        $this->finalCalculation($id);
    }


    /* add the material row */
    public function add($i)
    {
        if ($this->inputs && isset($this->inputs[$i])) {
            $this->inputs[$i] = $this->inputs[$i];
            $available = $this->availablility($i);
            $current = $this->quantity[$i];
            if ($current >= $available) {
                $this->dispatchBrowserEvent('swal', ['title' => 'Available Items:' . $available]);
                return 1;
            } else {
                $this->quantity[$i] =  $this->quantity[$i] + 1;
            }
        } else {
            $this->inputs[$i] = 1;
            $this->total_items = $this->total_items + 1;
        }
        $this->finalCalculation($i);
    }

    //check total stock that is available
    public function availablility($id)
    {
        $mate = Material::find($id);
        $opening_stock = $mate->opening_stock;
        $purchase = PurchaseDetail::where('material_id', $id)->sum('purchase_quantity');
        $sales = InvoiceDetail::where('item_id', $id)->where('type', 2)->sum('quantity');
        $stock_adjustment_deduction = StockAdjustmentDetail::where('material_id', $id)->where('type', 1)->sum('quantity');
        $stock_adjustment_addition = StockAdjustmentDetail::where('material_id', $id)->where('type', 2)->sum('quantity');
        $transferred = StockTransferDetail::where('material_id', $id)->sum('quantity');
        $returned = SalesReturnDetail::where('item_id', $id)->where('type', 2)->sum('quantity');
        $available = ($opening_stock + $stock_adjustment_addition + $purchase + $returned) - ($transferred + $stock_adjustment_deduction + $sales);
        return $available;
    }

    //change quantity of row
    public function changeQuantity($id)
    {
        $available = $this->availablility($id);
        $current = $this->quantity[$id];
        $old = 0;
        if ($current > $available) {
            $this->quantity[$id] = number_format(($available + $old), 2);
            $this->dispatchBrowserEvent('swal', ['title' => 'Available Quantity: ' . number_format(($available + $old), 2)]);
            return 1;
        }
        if ($this->quantity[$id] == '') {
            $this->quantity[$id] = number_format($available, 2);
        }
        $this->finalCalculation($id);
    }

    //save stock transfer
    public function save()
    {
        $this->validate([
            'branch_id'  => 'required',
        ]);
        if (count($this->inputs) == 0) {
            $this->dispatchBrowserEvent('swal', ['title' => 'Material List Cannot be empty!']);
            return 1;
        }
        $this->calculation();
        $this->emit('savemessage', ['type' => 'success', 'title' => 'Success', 'message' => 'Stock Transferred Successfully!']);
        return redirect()->route('admin.stock_transfer');
    }

    //calculate stock
    public function calculation()
    {
        $branch_test = User::find($this->branch_id);
        if ($branch_test) {
            $transfer = new StockTransfer();
            $transfer->warehouse_from = Auth::user()->id;
            $transfer->warehouse_to = $this->branch_id;
            $transfer->date = $this->date;
            $transfer->total_quantity = $this->total_quantity;
            $transfer->created_by = Auth::user()->id;
            $transfer->financial_year_id = getFinancialYearID();
            $transfer->save();
            $this->stock_transfer_id = $transfer->id;
            foreach ($this->inputs as $key => $value) {
                $detail = new StockTransferDetail();
                $detail->stock_transfer_id = $this->stock_transfer_id;
                $detail->material_id = $this->material_id[$key];
                $detail->material_name = $this->material_name[$key];
                $detail->quantity = $this->quantity[$key];
                $detail->material_unit = $this->material_unit[$key];
                $detail->save();
            }
            $updateTotalItem = StockTransfer::find($transfer->id);
            $updateTotalItem->total_items = $this->total_items;
            $updateTotalItem->save();
        } else {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Invalid Branch!']
            );
            return 1;
        }
    }
}