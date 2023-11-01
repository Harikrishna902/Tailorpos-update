<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Models\Material;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Translation;

class PurchaseEdit extends Component
{
    public $suppliers,$supplier,$name,$phone,$email,$tax,$address,$is_active=1,$opening_balance,$search='',$current_id,$purchase,$tax_percentage;
    public $purchase_number,$service_charge,$discount,$gross_amount,$supplier_id,$purchase_date,$sub_total=0;
    public $supplier_query,$material_query,$suppliers_results,$material_results,$chosenSupplier,$purchase_id,$total_quantity;
    public $inputs = [],$material_name = [],$material_unit = [],$quantity=[],$price =[],$material,$materials,$material_code = [],$material_id=[],$tax_amount = [],$tax_total,$total=[];

    //render the page
    public function render()
    {
        return view('livewire.admin.purchase.purchase-edit');
    }

    // Initializes the component, setting the default language , load purchase.
    public function mount($id){
        $this->purchase = Purchase::where('created_by',Auth::user()->id)->where('id',$id)->first();
        if(!($this->purchase)){
            abort(404);
        }
        $this->current_id = $id;
        $this->supplier_id = $this->purchase->supplier_id;
        $this->purchase_number = $this->purchase->purchase_number;
        $this->purchase_date = $this->purchase->purchase_date;
        $this->sub_total = $this->purchase->sub_total;
        $this->discount = $this->purchase->discount;
        $this->tax_percentage = $this->purchase->tax_percentage;
        $this->tax_total = $this->purchase->tax_amount;
        $this->total_quantity = $this->purchase->total_quantity;
        $this->service_charge = $this->purchase->service_charge;
        $this->gross_amount = $this->purchase->total;
        $this->purchase_id = $id;
        $supplier = Supplier::find($this->supplier_id);
        $this->supplier_query = $supplier->name;
        $details = \App\Models\PurchaseDetail::where('purchase_id',$this->current_id)->get();
        foreach($details as $detail) {
            $key = $detail->material_id;
            $this->material_id[$key] = $detail->material_id;
            $this->material_name[$key] = $detail->material_name;
            $this->quantity[$key] = $detail->purchase_quantity;
            $this->price[$key] = $detail->purchase_price;
            $this->tax_amount[$key] = $detail->tax_amount;
            $this->material_unit[$key] = $detail->material_unit;
            $this->total[$key] = $detail->purchase_item_total;
            $this->add($key);
       } 
    }

    // Validates and adds a new supplier to the database.
    public function addSupplier()
    {
        $this->validate([
            'name'  => 'required',
            'phone'  => 'required',
            'email'  => 'nullable|email|unique:suppliers,email'
        ]);
        if($this->opening_balance == '' || $this->opening_balance == null)
        {
            $this->opening_balance = 0;
        }
        if($this->email == '')
        {
            $this->email = null;
        }
        Supplier::create([
            'name'  => $this->name,
            'phone' => $this->phone,
            'email'  => $this->email ?? null,
            'tax_number' => $this->tax,
            'supplier_address'  => $this->address,
            'is_active' => $this->is_active,
            'opening_balance' => $this->opening_balance,
            'created_by'    => Auth::user()->id
        ]);
        $this->resetFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Supplier has been created!']);
    }

    // Resets the input fields for adding a supplier.
    public function resetFields()
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->tax = '';
        $this->address = '';
        $this->is_active = 1;
        $this->opening_balance = '';
        $this->resetErrorBag();
    }

    /* update value while change input fields */
    public function updated($name,$value)
    {
    if ( $value == '' ) data_set($this, $name, null);
        if($name == 'supplier_query' && $value != '')
        {
            $this->supplier_id = "";
            $this->suppliers_results = Supplier::where('created_by',Auth::user()->id)->where('name', 'like',  $this->supplier_query . '%')->where('is_active',1)->get();
        }
        if($name == 'supplier_query' && $value == ''){
            $this->suppliers_results = '';
            $this->supplier_id = "";
        }
        if($name == "material_query" && $value != '') {
            $this->material_results = Material::where('created_by',Auth::user()->id)->where('name', 'like', $value . '%')->where('is_active',1)->get();
            }
            if($name == "material_query" && $value == '') {
            $this->material_results = '';
            }

            if($name == "discount") {
            $this->finalCalculation();
            }
            if($name == "service_charge") {
            $this->finalCalculation();
            }
    }

    /* select supplier */
    public function selectSupplier($id)
    {
        $this->chosenSupplier = Supplier::where('id',$id)->first();
        $this->suppliers_results = '';
        $this->supplier_id = $this->chosenSupplier->id;
        $this->supplier_query=$this->chosenSupplier->name??"";
        $this->emit('closeDropdown');
    }

    /* select the material */
    public function selectMaterial($id)
    {
        $this->material_results = '';
        $this->material_query = '';
        $this->selectedMaterial($id);
    }
    
    /* set the select the material */
    public function selectedMaterial($item)
    {
        if ($item) {
            $base_material = Material::where('id',$item)->first();
            if($base_material) {       
                if(!isset($this->material_name[$base_material->id]))
                {
                    $this->material_id[$base_material->id] = $base_material->id;
                    $this->material_name[$base_material->id] = $base_material->name;
                    $this->material_unit[$base_material->id] = $base_material->unit;
                    $this->quantity[$base_material->id] = 1;
                    $this->material_code[$base_material->id] = $base_material->supplier_code;
                    $this->price[$base_material->id] = $base_material->price;
                    $this->rowCalculation($base_material->id);
                }
                $this->add($base_material->id);
            }
        }
    }

    // Calculates tax, total, and updates related properties for a material row.
    public function rowCalculation($id) {
        $this->tax_amount[$id] = $this->price[$id] * $this->quantity[$id] * ($this->tax_percentage/100) ;
        $this->total[$id] = $this->tax_amount[$id]+($this->price[$id] * $this->quantity[$id]);
        $this->finalCalculation();
    }

    // Performs the final calculation of sub-total, tax, and gross amount.
    public function finalCalculation(){
        if(($this->discount=='') || ($this->discount==NULL)){
            $this->discount=0;
        }
        if(($this->service_charge=='') || ($this->service_charge==NULL)){
            $this->service_charge=0;
        }
         $this->sub_total = 0;
         $this->tax_total = 0;
         $this->total_quantity = 0;
        foreach($this->inputs as $key => $value) {
            $tax_amount_current = (($this->price[$key] * $this->quantity[$key]) * ($this->tax_percentage/100));
            $this->tax_total = $this->tax_total +  $tax_amount_current;
            $this->sub_total = $this->sub_total + ($this->price[$key] * $this->quantity[$key]);
            $this->total_quantity = $this->total_quantity +  $this->quantity[$key];
        }
        $this->gross_amount = $this->tax_total + $this->sub_total + $this->service_charge - $this->discount;
    }

    /* delete the material row */
    public function delete($id)
    {
        if(isset($this->inputs[$id]))
        {
            unset($this->inputs[$id]);
            unset($this->material_name[$id]);
            unset($this->material_code[$id]);
            unset($this->quantity[$id]);
            unset($this->price[$id]);
        }
        $this->finalCalculation($id);
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
        $this->rowCalculation($i);
    }

    // Handles changes in the quantity of a material.
    public function changeQuantity($id){
        if(($this->quantity[$id]<1) || ($this->quantity=='')){
            $this->quantity[$id] = 1;
        } 
        $this->rowCalculation($id);
    }

    // Handles changes in the price of a material.
    public function changePrice($id){
        $this->rowCalculation($id);
    }

    // Validates and saves the purchase as a draft.
    public function saveAsDraft(){
        $this->validate([
            'supplier_id'  => 'required',
            'purchase_number'  => 'required',
            'purchase_date'  => 'required'
        ]);
        if(count($this->inputs) == 0)
        {
            $this->dispatchBrowserEvent('swal', ['title' => 'Material List Cannot be empty!']);
            return 1;
        }
        $this->calculation();
         $purchase = Purchase::find($this->purchase_id);
         $purchase->purchase_type = 1;
         $purchase->save();
         $this->purchase_id = "";
         $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Purchase Saved As Draft successfully!']);
        return redirect()->route('admin.purchases');
    }

    // Validates and saves the purchase as pushed.
    public function saveAsPushed(){
        $this->emit('closemodal');
        $this->validate([
            'supplier_id'  => 'required',
            'purchase_number'  => 'required',
            'purchase_date'  => 'required'
        ]);
        if(count($this->inputs) == 0)
        {
            $this->dispatchBrowserEvent('swal', ['title' => 'Material List Cannot be empty!']);
            return 1;
        }
        $this->calculation();
         $purchase = Purchase::find($this->purchase_id);
         $purchase->purchase_type = 2;
         $purchase->save();
         $this->purchase_id = "";
         $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Purchase saved As Pushed successfully!']);
         return redirect()->route('admin.purchases');
    }

    // Performs calculations and saves the purchase and its details.
    public function calculation(){
        $supplier_test = Supplier::find($this->supplier_id);
        if($supplier_test) {
            $purchase = Purchase::find($this->current_id);
            $purchase->supplier_id = $this->supplier_id;
            $purchase->purchase_number = $this->purchase_number;
            $purchase->purchase_date = $this->purchase_date;
            $purchase->sub_total = $this->sub_total;
            $purchase->discount = $this->discount;
            $purchase->tax_percentage = $this->tax_percentage;
            $purchase->tax_amount = $this->tax_total;
            $purchase->total_quantity = $this->total_quantity;
            $purchase->service_charge = $this->service_charge;
            $purchase->total = $this->gross_amount;
            $purchase->created_by = Auth::user()->id;
            $purchase->financial_year_id = getFinancialYearID();
            $purchase->save();
            $this->purchase_id = $purchase->id;
            $deleteDetail = PurchaseDetail::where('purchase_id',$this->current_id)->get();
            foreach($deleteDetail as $row) {
                $row->delete();
            }
            foreach($this->inputs as $key => $value) {
                $detail = new PurchaseDetail();
                $detail->purchase_id = $this->purchase_id;
                $detail->material_id = $this->material_id[$key];
                $detail->material_name = $this->material_name[$key];
                $detail->purchase_quantity = $this->quantity[$key];
                $detail->purchase_price = $this->price[$key];
                $detail->tax_amount = $this->tax_amount[$key];
                $detail->material_unit = $this->material_unit[$key];
                $detail->purchase_item_total = $this->total[$key];
                $detail->save();
           } 
        }else {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'message' => 'Invalid Supplier!']);
                    return 1;
        }
    }
}