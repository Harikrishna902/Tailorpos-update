<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Invoice;
use App\Models\MasterSetting;
use App\Models\Translation;
use Livewire\Component;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesView extends Component
{
    public $invoice,$firm_logo,$firm_name,$tax,$inv_id;

    //render the page
    public function render()
    {
        return view('livewire.admin.sales.sales-view');
    }

    //load viewing sale  if it can be viewed by logged in user
    public function mount($id)
    {
        if(Auth::user()->user_type==2) {
            $this->invoice = Invoice::where('id',$id)->first();
        }
        if(Auth::user()->user_type==3) {
            $this->invoice = Invoice::where('id',$id)->where('created_by',Auth::user()->id)->first();
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

    //print invoice
    public function printInvoice(){
        $this->emit('printWindow',$this->invoice->id);
    }

    //download invoice
    public function downloadPdf(){
        $id  = $this->invoice->id;
        $pdfContent = Pdf::loadView('livewire.admin.invoice.download-pdf',compact('id'))->output();
       return response()->streamDownload(fn () => print($pdfContent), "Invoice.pdf");
    }
      //download invoice
      public function downloadPdfA4(){
        $id  = $this->invoice->id;
        $pdfContent = Pdf::loadView('livewire.admin.invoice.download-pdf-a4',compact('id'))->output();
       return response()->streamDownload(fn () => print($pdfContent), "Invoice.pdf");
    }
}