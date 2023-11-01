<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\InvoicePayment;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$invoices,$payment_mode='';

    //render the page & data
    public function render()
    {
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $payments = InvoicePayment::whereDate('date','>=',$start_date)->whereDate('date','<=',$end_date)->latest();
        if($this->branch != '')
        {
            $payments->where('created_by',$this->branch);
        }
        if($this->payment_mode != '')
        {
            $payments->where('payment_mode',$this->payment_mode);
        }
        $this->payments = $payments->orderBy('voucher_no','asc')->get();
        return view('livewire.admin.reports.payment-report');
    }

    //set default start dates,end dates,
    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
        /* if the user is branch */
        $this->branches = User::whereIn('user_type',[3,2])->get();
        /* if the user is not admin */
        if(Auth::user()->user_type != 2)
        {
            $this->branch = Auth::user()->id;
        }
    }

    /* download pdf file */
    public function downloadFile()
    {
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $branch = $this->branch;
        $payment_mode = $this->payment_mode;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.payment-report', compact('start_date', 'end_date','branch','payment_mode'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "PaymentReport_from_" . $start_date . ".pdf");
    }
}