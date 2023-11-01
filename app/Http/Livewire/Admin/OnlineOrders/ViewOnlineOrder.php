<?php

namespace App\Http\Livewire\Admin\OnlineOrders;

use App\Models\MasterSetting;
use App\Models\OnlineOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewOnlineOrder extends Component
{
    public $invoice, $firm_logo, $firm_name, $tax, $inv_id,$order_status;
    public function render()
    {
        return view('livewire.admin.online-orders.view-online-order');
    }

    //load viewing sale  if it can be viewed by logged in user
    public function mount($id)
    {
        if (Auth::user()->user_type == 2) {
            $this->invoice = OnlineOrder::where('id', $id)->first();
        }
        if (Auth::user()->user_type == 3) {
            $this->invoice = OnlineOrder::where('id', $id)->where('branch_id', Auth::user()->id)->first();
        }
        if (!$this->invoice) {
            abort(404);
        }
        $this->order_status = $this->invoice->status;
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->firm_name = $site['company_name'] ?? 'Tailor POS';
        $this->tax = $site['company_tax_registration'] ?? '';
        $this->firm_logo = asset($site['company_logo'] ?? '/assets/images/sample.jpg') ?? '/assets/images/sample.jpg';
    }

    //print invoice
    public function printInvoice()
    {
        $this->emit('printWindow', $this->invoice->id);
    }


    //download invoice
    public function downloadPdf(){
    $id  = $this->invoice->id;
    $pdfContent = Pdf::loadView('livewire.admin.online-orders.download-online-order',compact('id'))->output();
       return response()->streamDownload(fn () => print($pdfContent), "OnlineOrder.pdf");
    }
   
    /* change status */
    public function changeStatus(){
      if($this->invoice) {
        $this->invoice->status = $this->order_status;
        $this->invoice->save();
        $this->dispatchBrowserEvent('alert',['type' => 'success','title' => 'Success','message' => 'Order Status Changed Successfully!']);
      }
    }
}