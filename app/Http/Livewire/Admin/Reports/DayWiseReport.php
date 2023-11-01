<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class DayWiseReport extends Component
{
    public $mycollection,$branch,$start_date,$end_date,$invoices,$no_of_invoices,$total_sales,$payment_received,$total_expense,$branches;
    public $gross_expense,$gross_payments,$gross_sales,$gross_invoices;

    //render the page
    public function render()
    {
        $this->mycollection = [];
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $period = CarbonPeriod::create($start_date,$end_date);
        $this->gross_expense = 0;
        $this->gross_payments = 0;
        $this->gross_sales = 0;
        $this->gross_invoices = 0;
        foreach($period as $row)
        {
            $no_of_invoices = Invoice::whereDate('date',$row->toDateString())->latest();
            $total_sales = Invoice::whereDate('date',$row->toDateString())->latest();
            $payment_received = InvoicePayment::whereDate('date',$row->toDateString())->latest();
            $total_expense = Expense::whereDate('date',$row->toDateString())->latest();
            if($this->branch != '')
            {
                $no_of_invoices->where('created_by',$this->branch);
                $total_sales->where('created_by',$this->branch);
                $payment_received->where('created_by',$this->branch);
                $total_expense->where('created_by',$this->branch);
            }
            $this->no_of_invoices = $no_of_invoices->count();
            $this->total_sales = $total_sales->sum('total');
            $this->payment_received = $payment_received->sum('paid_amount');
            $this->total_expense = $total_expense->sum('amount');
            $this->gross_expense += $this->total_expense;
            $this->gross_payments += $this->payment_received;
            $this->gross_sales += $this->total_sales;
            $this->gross_invoices += $this->no_of_invoices;
            if($this->no_of_invoices!= 0 || $this->total_sales != 0 || $this->payment_received != 0 || $this->total_expense != 0 )
            {
                $items = [
                    'invoices'  => $this->no_of_invoices,
                    'sales' => getFormattedCurrency($this->total_sales),
                    'payments'  => getFormattedCurrency($this->payment_received),
                    'expense'   => getFormattedCurrency($this->total_expense)
                ];
                $this->mycollection[$row->format('d/m/Y')] = $items;
            }
        }
        return view('livewire.admin.reports.day-wise-report');
    }

    //set default start & end dates and branches
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
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.daywise-report', compact('start_date', 'end_date','branch'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "DaywiseReport_from_" . $start_date . ".pdf");
    }
}