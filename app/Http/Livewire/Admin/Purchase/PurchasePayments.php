<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\Auth;

class PurchasePayments extends Component
{

    public $suppliers, $supplier, $name, $phone, $email, $tax, $address, $is_active = 1, $opening_balance, $search = '', $date, $material_query, $inputi = 1;
    public $purchase_number, $service_charge, $discount, $gross_amount, $supplier_id, $purchase_date, $sub_total = 0;
    public $supplier_query, $purchase_query, $suppliers_results, $material_results, $chosenSupplier, $chosenPurchase, $purchase_results, $purchase_id, $total_quantity, $chosenMaterial;
    public $inputs = [], $material_name = [], $material_unit = [], $quantity = [], $price = [], $material, $materials, $material_code = [], $material_id = [], $tax_amount = [], $tax_total, $total = [];
    public $quantity_current = [];
    public $payment, $paid_amount, $payment_mode, $reference_number, $editMode = false;

    //render the page and load supplier payments
    public function render()
    {
        $query = SupplierPayment::where('created_by', Auth::user()->id)->latest();
        if ($this->search != '') {
            $value = $this->search;
            $query->whereHas('supplier', function ($query1) use ($value) {
                $query1->where('name', 'LIKE', '%' . $value . '%')
                    ->orWhere('tax_number', 'like', '%' . $value . '%');;
            });
        }
        $this->purchases = $query->get();
        return view('livewire.admin.purchase.purchase-payments');
    }

    //initialize date
    public function mount()
    {
        $this->date = \Carbon\Carbon::today()->toDateString();
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = \App\Models\Translation::where('default', 1)->first();
        }
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
    }

    /* select supplier */
    public function selectSupplier($id)
    {
        $this->chosenSupplier = Supplier::where('id', $id)->first();
        $this->suppliers_results = null;
        $this->supplier_id = $this->chosenSupplier->id;
        $this->supplier_query = $this->chosenSupplier->name ?? "";
        $this->emit('closeDropdown');
    }

    //save payment
    public function save()
    {
        if ($this->editMode == false) {
            $this->validate([
                'payment_mode' => 'required',
                'paid_amount' => 'required'
            ]);
            if ($this->supplier_id == '') {
                $this->addError('supplier', 'Select Supplier!');
                return false;
            }
            $supplier = Supplier::find($this->supplier_id);
            if ($supplier) {
                $paid_inline = \App\Models\SupplierPayment::where('supplier_id', $this->supplier_id)->sum('paid_amount');
                $total_inline = \App\Models\Purchase::where('purchase_type', 2)->where('supplier_id', $this->supplier_id)->sum('total');
                $openbal = \App\Models\Supplier::where('id', $this->supplier_id)->first()->opening_balance;
                $balance_inline = ($total_inline + $openbal) - $paid_inline;
                if ($this->paid_amount > $balance_inline) {
                    $this->addError('balance', 'Amount Cannot greater than Balance Amount');
                    return false;
                }
                $payment =  new SupplierPayment();
                $payment->supplier_id = $this->supplier_id;
                $payment->supplier_name = $supplier->name;
                $payment->date = $this->date;
                $payment->paid_amount = $this->paid_amount;
                $payment->payment_mode = $this->payment_mode;
                $payment->reference_number = $this->reference_number;
                $payment->payment_mode = $this->payment_mode;
                $payment->financial_year_id = getFinancialYearID();
                $payment->created_by = Auth::user()->id;
                $payment->save();
            }
            if ($payment) {
                $this->resetInputFields();
                $this->emit('closemodal');
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'success',  'message' => 'Payment Created Successfully!']
                );
            }
        }

        //If user clicked on the edit button
        else if ($this->editMode == true) {
            $idd = $this->payment->id;
            $this->validate([
                'payment_mode' => 'required',
                'paid_amount' => 'required'
            ]);
            if ($this->supplier_id == '') {
                $this->addError('supplier', 'Select Supplier!');
                return false;
            }
            $supplier = Supplier::find($this->supplier_id);

            if ($supplier) {
                $old_payment = $this->payment->paid_amount;
                $paid_inline = \App\Models\SupplierPayment::where('supplier_id', $this->supplier_id)->sum('paid_amount');
                $total_inline = \App\Models\Purchase::where('purchase_type', 2)->where('supplier_id', $this->supplier_id)->sum('total');
                $openbal = \App\Models\Supplier::where('id', $this->supplier_id)->first()->opening_balance;
                $balance_inline = ($total_inline + $openbal) - ($paid_inline - $old_payment);
                if ($this->paid_amount > $balance_inline) {
                    $this->addError('balance', 'Amount Cannot greater than Balance Amount');
                    return false;
                }
                $payment =  SupplierPayment::find($idd);
                $payment->supplier_id = $this->supplier_id;
                $payment->supplier_name = $supplier->name;
                $payment->date = $this->date;
                $payment->paid_amount = $this->paid_amount;
                $payment->payment_mode = $this->payment_mode;
                $payment->reference_number = $this->reference_number;
                $payment->payment_mode = $this->payment_mode;
                $payment->financial_year_id = getFinancialYearID();
                $payment->created_by = Auth::user()->id;
                $payment->save();
            }
            if ($payment) {
                $this->resetInputFields();
                $this->emit('closemodal');
                $this->editMode = false;
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'success',  'message' => 'Payment Created Successfully!']
                );
            }
        }
    }

    //Reset Input Fields
    public function resetInputFields()
    {
        $this->paid_amount = '';
        $this->supplier_id = '';
        $this->reference_number = '';
        $this->supplier_query = "";
        $this->date = \Carbon\Carbon::today()->toDateString();
        $this->resetErrorBag();
        $this->editMode = false;
    }
    //If user clicked on the edit button get item id and initialize input variables with it.
    public function edit($id)
    {
        $this->editMode = true;
        $this->resetErrorBag();
        $this->payment = SupplierPayment::find($id);
        $this->reference_number = $this->payment->reference_number;
        $this->paid_amount = $this->payment->paid_amount;
        $this->date = \Carbon\Carbon::parse($this->payment->date)->toDateString();
        $this->payment_mode = $this->payment->payment_mode;
        $this->supplier_id = $this->payment->supplier_id;
        $this->selectSupplier($this->supplier_id);
    }

    //delete payment
    public function delete()
    {
        if ($this->payment) {
            $this->payment->delete();
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'success',  'message' => 'Payment has been deleted!']
            );
            $this->payment = null;
            $this->emit('closemodal');
        }
    }

    //load payment for delete confirmation
    public function confirmDelete($id)
    {
        $this->payment = SupplierPayment::find($id);
    }
}