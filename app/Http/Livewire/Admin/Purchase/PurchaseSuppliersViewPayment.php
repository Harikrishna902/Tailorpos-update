<?php

namespace App\Http\Livewire\Admin\Purchase;

use Livewire\Component;
use Auth;
use App\Models\Supplier;
use App\Models\SupplierPayment;

class PurchaseSuppliersViewPayment extends Component
{
    public $suppliers, $supplier;
    public $payment, $paid_amount, $payment_mode, $reference_number;
    protected $listeners = ['resetPayments' => 'reloadPayments'];

    //render the page
    public function render()
    {
        return view('livewire.admin.purchase.purchase-suppliers-view-payment');
    }

    //load supplier payments
    public function mount($id)
    {
        /* if the user is not admin */
        if (Auth::user()->user_type != 2) {
            abort(404);
        }
        /* if the user is admin */
        if (Auth::user()->user_type == 2) {
            $this->supplier = Supplier::find($id);
        }
        if (!$this->supplier) {
            abort(404);
        }
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = \App\Models\Translation::where('default', 1)->first();
        }

        $this->supplier_id = $id;
        $this->payments = SupplierPayment::where('supplier_id', $this->supplier_id)->latest()->get();
    }

    //prepare supplier for edit
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
    }

    //delete payment
    public function delete()
    {
        if ($this->payment) {
            $this->payment->delete();
            $this->payments = SupplierPayment::where('supplier_id', $this->supplier_id)->latest()->get();
            $this->emit('resetPage');
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

    //save payment
    public function save()
    {
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
                $this->addError('balance', 'Amount Cannot be greater than Balance Amount');
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
            $this->payments = SupplierPayment::where('supplier_id', $this->supplier_id)->latest()->get();
            $this->resetInputFields();
            $this->emit('resetPage');
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'success',  'message' => 'Payment Created Successfully!']
            );
        }
    }

    //Reset Input Fields
    public function resetInputFields()
    {
        $this->paid_amount = '';
        $this->supplier_id = '';
        $this->reference_number = '';
        $this->date = \Carbon\Carbon::today()->toDateString();
        $this->resetErrorBag();
    }

    //reload supplier payments
    public function reloadPayments()
    {
        $this->payments = SupplierPayment::where('supplier_id', $this->supplier_id)->latest()->get();
    }
}