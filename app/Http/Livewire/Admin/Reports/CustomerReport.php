<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\Customer;
use App\Models\CustomerPaymentDiscount;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\SalesReturnPayment;
use App\Models\Translation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerReport extends Component
{
    public $customer_query,$customers,$selected_customer,$start_date,$end_date,$mycollection,$downloadValue,$download_id;

    //render the page
    public function render()
    {
        $this->mycollection = [];
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $period = CarbonPeriod::create($start_date,$end_date);
        if($this->selected_customer)
        {
            foreach($period as $row)
            {
                $invoice = Invoice::whereDate('date',$row->toDateString())->where('customer_id',$this->selected_customer->id)->latest()->get();
                $payment = InvoicePayment::whereDate('date',$row->toDateString())->where('customer_id',$this->selected_customer->id)->latest()->get();
                $salesreturn = SalesReturnPayment::whereDate('date',$row->toDateString())->where('customer_id',$this->selected_customer->id)->latest()->get();
                $discount = CustomerPaymentDiscount::whereDate('date',$row->toDateString())->where('customer_id',$this->selected_customer->id)->latest()->get();
                if(count($invoice) >0 || count($payment) >0 || count($discount) >0 )
                {
                    $items = [
                        'invoice'  =>$invoice,
                        'payment' => $payment,
                        'discount'  => $discount,
                        'sales_return'  => $salesreturn,
                    ];
                    $this->mycollection[$row->format('d/m/Y')] = $items;
                }
            }
            $this->mycollection = array_reverse($this->mycollection);
        }
        return view('livewire.admin.reports.customer-report');
    }

    //set start date and end date
    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    //select customer from dropdown
    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::where('is_active',1)->where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = null;
        $this->download_id = $id;
    }

    //update customer list on query change
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

    /* download pdf file */
    public function downloadFile()
    {
        $id  = $this->download_id;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.customer-report', compact('id','start_date','end_date'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "CustomerReport.pdf");
    }
}
