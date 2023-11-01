<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Invoice;
use App\Models\MasterSetting;
use App\Models\SalesReturn;
use App\Models\SalesReturnPayment;
use App\Models\Translation;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SalesReturnView extends Component
{
    public $invoice,$firm_logo,$firm_name,$tax,$inv_id;

    //render the page
    public function render()
    {
        return view('livewire.admin.sales.sales-return-view');
    }
    // process on mount
    public function mount($id)
    {
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->invoice = SalesReturn::where('id',$id)->first();
        }
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->invoice = SalesReturn::where('id',$id)->where('created_by',Auth::user()->id)->first();
        }
        if(!$this->invoice)
        {
            abort(404);
        }
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->firm_name = $site['company_name'] ?? 'Tailor POS';
        $this->tax = $site['company_tax_registration'] ?? '';
        $this->firm_logo = asset($site['company_logo'] ?? '/assets/images/sample.jpg') ?? '/assets/images/sample.jpg';
    }

    //print sales return
    public function printInvoice(){
        $this->emit('printWindow',$this->invoice->id);
    }

    //refund sales return
    public function refund()
    {
        if(SalesReturnPayment::where('sales_return_id',$this->invoice->id)->count() > 0)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error','title'=> 'No!',  'message' => 'The payment was already refunded!']);
            return 1;
        }
        else{
            SalesReturnPayment::create([
                'date' => Carbon::now(),
                'invoice_id'    => $this->invoice->invoice_id,
                'customer_name' => $this->invoice->customer_name,
                'customer_id'   => $this->invoice->customer_id,
                'created_by'    => Auth::user()->id,
                'financial_year_id' => getFinancialYearID(),
                'branch_id' => Auth::user()->id,
                'paid_amount'   => $this->invoice->total,
                'sales_return_id'   => $this->invoice->id,
            ]);
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success','title'=> 'Payment Refunded!',  'message' => 'The payment was refunded successfully!']);
            return 1;
        }
    }
}