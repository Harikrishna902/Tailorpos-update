<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\SupplierPayment;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
class PurchasePaymentReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$payments,$payment_mode='';

    //render the page & data
    public function render()
    {
        $payments = SupplierPayment::whereDate('created_at','>=',$this->start_date)->whereDate('created_at','<=',$this->end_date)->latest();
        if($this->payment_mode != '')
        {
            $payments->where('payment_mode',$this->payment_mode);
        }
        $this->payments = $payments->get();
        return view('livewire.admin.reports.purchase-payment-report');
    }

    //set default start dates,end dates,
    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

     /* download pdf file */
     public function downloadFile()
     {
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $payment_mode = $this->payment_mode;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.purchase-payment-report', compact('start_date', 'end_date','payment_mode'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "PurchasePaymentReport_from_" . $start_date . ".pdf");
     }
}