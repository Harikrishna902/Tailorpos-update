<?php

namespace App\Http\Livewire\Admin\Ledger;

use App\Models\Customer;
use App\Models\CustomerPaymentDiscount;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\SalesReturn;
use App\Models\SalesReturnPayment;
use App\Models\Translation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;

class Ledgers extends Component
{
    public $customer_query,$customers,$selected_customer,$start_date,$end_date,$mycollection,$mycollection2,$downloadValue,$download_id,$data;

    //render the page
    public function render()
    {
        $localdata = collect();
        if($this->selected_customer)
        {
            $invoice = Invoice::where('customer_id',$this->selected_customer->id)->latest()->get();
            $invoice->map(function ($invoice) {
                $invoice->identifier = 1;
                return $invoice;
            });
            $localdata = $localdata->concat($invoice);
            $payment = InvoicePayment::where('customer_id',$this->selected_customer->id)->latest()->get();
            $payment->map(function ($payment) {
                $payment->identifier = 2;
                return $payment;
            });
            $localdata = $localdata->concat($payment);
            $discount = CustomerPaymentDiscount::where('customer_id',$this->selected_customer->id)->latest()->get();
            $discount->map(function ($discount) {
                $discount->identifier = 3;
                return $discount;
            });
            $localdata = $localdata->concat($discount);
            $salesreturn = SalesReturn::where('customer_id',$this->selected_customer->id)->latest()->get();
            $salesreturn->map(function ($salesreturn) {
                $salesreturn->identifier = 4;
                return $salesreturn;
            });
            $localdata = $localdata->concat($salesreturn);
            $salesreturnpayment = SalesReturnPayment::where('customer_id',$this->selected_customer->id)->latest()->get();
            $salesreturnpayment->map(function ($payment) {
                $payment->identifier = 5;
                return $payment;
            });
            $localdata = $localdata->concat($salesreturnpayment);
            $data = $localdata->all();
            $sorted = array_values(Arr::sort($data, function ($value) {
                return $value['date'];
            }));
            $this->data = $sorted;
        } 
        return view('livewire.admin.ledger.ledgers');
    }

    //load default values to input fields
    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    //select the customer
    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::where('is_active',1)->where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = null;
        $this->download_id = $id;
    }

    //load customer to search when user types in the customer select box.
    public function updatedCustomerQuery($value)
    {
        if($value != '')
        {
            $query2 = Customer::latest();
            /* if the user is branch */
            if(Auth::user()->user_type==3) {
                $query2->where('created_by',Auth::user()->id);
            }
            $this->customers = $query2->where(function($query) use ($value){
                $query->where('file_number','like',$value.'%');
                $query->orwhere('first_name','like','%'.$value.'%');
                $query->orWhere('phone_number_1','like',$value.'%');
                $query->orWhere('phone_number_2','like',$value.'%');
            })
            ->where('is_active',1)->reorder()->orderby('file_number','ASC')->limit(8)->get();
            $this->downloadValue=$value;
        }
        else{
            $this->customers = null;
        }
    }
}