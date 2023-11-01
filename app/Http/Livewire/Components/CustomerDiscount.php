<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Customer;
use App\Models\CustomerPaymentDiscount;
use Auth;
use App\Models\Translation;

class CustomerDiscount extends Component
{
    public $customer,$customer_id,$is_active,$discount_date,$discount_amount;
    protected $listeners=['resetdiscount' => 'resetDiscount'];

    // render the page
    public function render()
    {
        return view('livewire.components.customer-discount');
    }

    //set customer and discount date
    public function mount($id){
        
        $this->customer=Customer::find($id);
        $this->customer_id = $id;
        $this->discount_date = \Carbon\Carbon::now()->toDateString();
    }

    //reset fields
    public function resetDiscount(){
       $this->discount_date = \Carbon\Carbon::now()->toDateString();
       $this->discount_amount = "";
    }

    //create discount
    public function addDiscount(){
        $this->validate([
            'discount_date'  => 'required',
            'discount_amount' => 'required|numeric',
        ]);
        $total = \App\Models\Invoice::where('customer_id',$this->customer->id)->sum('total');
        $paid = \App\Models\InvoicePayment::where('customer_id',$this->customer->id)->sum('paid_amount');
        $opening_balance = $this->customer->opening_balance!=''?$this->customer->opening_balance:0;
        $discount = \App\Models\CustomerPaymentDiscount::where('customer_id',$this->customer->id)->sum('amount');
        $balance = ($total+$opening_balance)-($discount+$paid);
        if($this->discount_amount>$balance) {
            $this->addError('discount','Discount Cannot be greater than Balance.');
            return false;
        }
        $discount = new CustomerPaymentDiscount();
        $discount->customer_id = $this->customer_id;
        $discount->date = $this->discount_date;
        $discount->amount = $this->discount_amount;
        $discount->financial_year_id = getFinancialYearID();
        $discount->created_by = Auth::user()->id;
        $discount->save();
        $this->resetDiscount();
        $this->emit('resetPage');
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
        'alert', ['type' => 'success',  'message' => 'Discount Added Successfully!']);
    }
}