<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class DailyReport extends Component
{
    public $branches,$branch,$no_of_invoices,$total_sales,$payment_received,$total_expense,$date,$invoices;

    //render the page
    public function render()
    {
        $no_of_invoices = Invoice::whereDate('date',$this->date)->latest();
        $total_sales = Invoice::whereDate('date',$this->date)->latest();
        $payment_received = InvoicePayment::whereDate('date',$this->date)->latest();
        $total_expense = Expense::whereDate('date',$this->date)->latest();
        $invoices = Invoice::whereDate('date',$this->date)->latest();
        if($this->branch != '')
        {
            $no_of_invoices->where('created_by',$this->branch);
            $total_sales->where('created_by',$this->branch);
            $payment_received->where('created_by',$this->branch);
            $total_expense->where('created_by',$this->branch);
            $invoices->where('created_by',$this->branch);
        }
        $this->invoices = $invoices->get();
        $this->no_of_invoices = $no_of_invoices->count();
        $this->total_sales = $total_sales->sum('total');
        $this->payment_received = $payment_received->sum('paid_amount');
        $this->total_expense = $total_expense->sum('amount');

        return view('livewire.admin.reports.daily-report');
    }

    //set default date, branches
    public function mount()
    {
        $this->date = Carbon::today()->toDateString();
        /* if the user is branch */
        $this->branches = User::whereIn('user_type',[3,2])->get();
        if(Auth::user()->user_type != 2)
        {
            $this->branch = Auth::user()->id;
        }
    }

    /* download pdf file */
    public function downloadFile()
    {
        $date = $this->date;
        $branch = $this->branch;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.daily-report', compact('date','branch'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "Daily Report : " . $date . ".pdf");
    }
}