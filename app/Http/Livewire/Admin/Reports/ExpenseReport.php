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

class ExpenseReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$expenses,$payment_mode='';

    //render the page & data
    public function render()
    {
        $expenses = Expense::whereDate('date','>=',$this->start_date)->whereDate('date','<=',$this->end_date)->latest();
        if($this->branch != '')
        {
            $expenses->where('created_by',$this->branch);
        }
        if($this->payment_mode != '')
        {
            $expenses->where('payment_mode',$this->payment_mode);
        }
        $this->expenses = $expenses->get();
        return view('livewire.admin.reports.expense-report');
    }

    //set default start dates,end dates,
    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
        /* if the user is branch */
        $this->branches = User::whereIn('user_type',[3,2])->get();
        $this->branch = Auth::user()->id;
    }

    /* download pdf file */
    public function downloadFile()
    {
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $branch = $this->branch;
        $payment_mode = $this->payment_mode;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.expense-report', compact('start_date', 'end_date','branch','payment_mode'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "ExpenseReport_from_" . $start_date . ".pdf");
    }
}
