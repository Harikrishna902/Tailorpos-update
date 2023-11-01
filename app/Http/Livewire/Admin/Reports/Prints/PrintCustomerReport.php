<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\Customer;
use App\Models\CustomerPaymentDiscount;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintCustomerReport extends Component
{
    public $customer_query,$customers,$selected_customer,$start_date,$end_date,$mycollection;
    
    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-customer-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date ,$end_date,$customer_id=null)
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
        $this->mycollection = [];
        $this->selected_customer = Customer::find($customer_id);
        /* if the user is not admin */
        if(Auth::user()->user_type !=2)
        {
            if($this->selected_customer->created_by != Auth::user()->id)
            {
                abort(404);
            }
        }
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $period = CarbonPeriod::create($start_date,$end_date);
        if($this->selected_customer)
        {
            foreach($period as $row)
            {
                $invoice = Invoice::whereDate('date',$row->toDateString())->where('customer_id',$this->selected_customer->id)->latest()->get();
                $payment = InvoicePayment::whereDate('date',$row->toDateString())->where('customer_id',$this->selected_customer->id)->latest()->get();
                $discount = CustomerPaymentDiscount::whereDate('date',$row->toDateString())->where('customer_id',$this->selected_customer->id)->latest()->get();
                if(count($invoice) >0 || count($payment) >0 || count($discount) >0 )
                {
                    $items = [
                        'invoice'  =>$invoice,
                        'payment' => $payment,
                        'discount'  => $discount,
                    ];
                    $this->mycollection[$row->format('d/m/Y')] = $items;
                }
            }
            $this->mycollection = array_reverse($this->mycollection);
        }
    }
}