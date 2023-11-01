<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Models\Material;
use App\Models\Purchase;
use App\Models\PurchaseDetail;

class PurchaseReturns extends Component
{

    public $suppliers, $supplier, $name, $phone, $email, $tax, $address, $is_active = 1, $opening_balance, $search = '', $date, $material_query, $inputi = 1;
    public $purchase_number, $service_charge, $discount, $gross_amount, $supplier_id, $purchase_date, $sub_total = 0;
    public $supplier_query, $purchase_query, $suppliers_results, $material_results, $chosenSupplier, $chosenPurchase, $purchase_results, $purchase_id, $total_quantity, $chosenMaterial;
    public $inputs = [], $material_name = [], $material_unit = [], $quantity = [], $price = [], $material, $materials, $material_code = [], $material_id = [], $tax_amount = [], $tax_total, $total = [];
    public $quantity_current = [];
    public $i = 0;

    //render the page
    public function render()
    {
        return view('livewire.admin.purchase.purchase-returns');
    }

    //add 1 to global index
    public function mount()
    {
        $this->addLocal($this->inputi);
        $this->date = \Carbon\Carbon::today()->toDateString();
    }

    /* update value while change input fields */
    public function updated($name, $value)
    {
        if ($name == 'supplier_query' && $value != '') {
            $this->supplier_id = "";
            $this->suppliers_results = Supplier::where('created_by', Auth::user()->id)->where('name', 'like',  $this->supplier_query . '%')->where('is_active', 1)->get();
        }
        if ($name == 'supplier_query' && $value == '') {
            $this->suppliers_results = '';
            $this->supplier_id = "";
            $this->purchase_results = '';
            $this->material_results = '';
        }
        if ($name == "purchase_query" && $value != '') {
            $this->purchase_results = Purchase::where('created_by', Auth::user()->id)->where('supplier_id', $this->supplier_id)->where('purchase_type', 2)->get();
        }
        if ($name == "purchase_query" && $value == '') {
            $this->purchase_results = '';
        }

        if ($name == "material_query" && $value != '') {
            $this->material_results = PurchaseDetail::where('purchase_id', $this->purchase_id)->where('material_name', 'like',  $value . '%')->get();
        }
        if ($name == "material_query" && $value == '') {
            $this->material_results = '';
        }
    }

    /* select supplier */
    public function selectSupplier($id)
    {
        $this->chosenSupplier = Supplier::where('id', $id)->first();
        $this->suppliers_results = '';
        $this->supplier_id = $this->chosenSupplier->id;
        $this->supplier_query = $this->chosenSupplier->name ?? "";
        $this->emit('closeDropdown');
    }

    /* select supplier */
    public function selectPurchase($id)
    {
        $this->chosenPurchase = Purchase::where('id', $id)->first();
        $this->purchase_results = '';
        $this->purchase_id = $this->chosenPurchase->id;
        $this->purchase_query = $this->chosenPurchase->purchase_number ?? "";
        $this->emit('closeDropdown');
    }

    /* select supplier */
    public function selectMaterial($id)
    {
        $this->chosenMaterial = PurchaseDetail::where('id', $id)->first();
        $this->material_results = '';
        $this->material_id = $this->chosenMaterial->id;
        $this->material_query = $this->chosenMaterial->material_name ?? "";
        $this->selectedMaterial($id);
        $this->emit('closeDropdown');
    }

    //increase global index
    public function addLocal($i)
    {
        $this->inputi = $i + 1;
        $this->inputs[$this->inputi] = 1;
        $this->quantity[$this->inputi] = 1;
    }

    /* set the select the material */
    public function selectedMaterial($item)
    {
        $this->addLocal($this->inputi);
        if ($item) {
            $base_material = PurchaseDetail::where('id', $item)->first();
            if ($base_material) {
                if (!isset($this->material_name[$base_material->id])) {
                    $this->material_name[$this->inputi] = $base_material->material_name;
                    $this->material_unit[$this->inputi] = $base_material->material_unit;
                    $this->quantity[$this->inputi] = $base_material->purchase_quantity;
                    $this->price[$this->inputi] = $base_material->purchase_price;
                }
                $this->addLocal($this->inputi);
            }
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
            unset($this->price[$id]);
        }
    }

    /* add the material row */
    public function add($i)
    {
        if($this->inputs && isset($this->inputs[$i]))
        {
            $this->inputs[$i] = $this->inputs[$i];
            $this->quantity[$i] =  $this->quantity[$i]+1;
        }
        else{
            $this->inputs[$i] = 1;
        }
    }

    //change quantity of cart item
    public function changeQuantity($id)
    {
        if (($this->quantity[$id] < 1) || ($this->quantity == '')) {
            $this->quantity[$id] = 1;
        }
        if (($this->quantity_current[$id] > $this->quantity[$id])) {
            $this->dispatchBrowserEvent('swal', ['title' => 'Return Item Quantity Must Be greater than Purchase Quantity!']);
            return 1;
        }
    }

    //change price
    public function changePrice($id)
    {
        $this->rowCalculation($id);
    }
}