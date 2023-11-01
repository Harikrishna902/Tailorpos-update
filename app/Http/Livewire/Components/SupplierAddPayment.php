<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Auth;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\Translation;

class SupplierAddPayment extends Component
{
    public $suppliers,$supplier;
    public $payment,$paid_amount,$payment_mode,$reference_number,$date;

    //render the page
    public function render()
    {
        return view('livewire.components.supplier-add-payment');
    }

    //set default date and find supplier
    public function mount($id){
        // if the user is not admin 
        if(Auth::user()->user_type != 2)
        {
            abort(404);
        }
        // if the user is admin
        if(Auth::user()->user_type==2) {
            $this->supplier=Supplier::find($id);
        }
        if(!$this->supplier)
        {
            abort(404);
        }
        $this->date = \Carbon\Carbon::today()->toDateString();
        $this->supplier_id = $id;
    }

    //save supplier payment
    public function save(){
        $this->validate([
            'payment_mode' => 'required',
            'paid_amount' => 'required'
        ]);
        if($this->supplier_id=='')
        {
            $this->addError('supplier','Select Supplier!');
            return false;
        }
        if($this->supplier) {
            $paid_inline = \App\Models\SupplierPayment::where('supplier_id',$this->supplier_id)->sum('paid_amount');
            $total_inline = \App\Models\Purchase::where('purchase_type',2)->where('supplier_id',$this->supplier_id)->sum('total');
            $openbal = \App\Models\Supplier::where('id',$this->supplier_id)->first()->opening_balance;
            $balance_inline = ($total_inline + $openbal) - $paid_inline;
            if($this->paid_amount>$balance_inline){
                $this->addError('balance','Amount Cannot be greater than Balance Amount');
                return false;
            }
            $payment =  new SupplierPayment();
            $payment->supplier_id = $this->supplier_id;
            $payment->supplier_name = $this->supplier->name;
            $payment->date = $this->date;
            $payment->paid_amount = $this->paid_amount;
            $payment->payment_mode = $this->payment_mode;
            $payment->reference_number = $this->reference_number;
            $payment->payment_mode = $this->payment_mode;
            $payment->financial_year_id = getFinancialYearID();
            $payment->created_by = Auth::user()->id;
            $payment->save();
        }
        if($payment)
        {
            $this->resetInputFields();
            $this->emit('resetPage');
            $this->emit('resetPayments');
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Payment Created Successfully!']);
        }
    }

    //Reset Input Fields
    public function resetInputFields()
    {
        $this->paid_amount = '';
        $this->payment_mode = "";
        $this->reference_number= '';
        $this->date= \Carbon\Carbon::today()->toDateString();
        $this->resetErrorBag();
    }
}