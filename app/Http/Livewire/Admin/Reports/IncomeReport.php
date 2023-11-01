<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class IncomeReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$expenses,$payment_mode='',$gross_sales,$gross_expense,$net_income,$mycollection;
    public $expensecollect;

    //render the page
    public function render()
    {
        $this->mycollection = [];
        $this->expensecollect = [];
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $period = CarbonPeriod::create($start_date,$end_date);
        $this->gross_expense = 0;
        $this->gross_sales = 0;
        $this->net_income = 0;
        foreach($period as $row)
        {
            $no_of_invoices = Invoice::whereDate('date',$row->toDateString())->latest();
            $total_sales = Invoice::whereDate('date',$row->toDateString())->latest();
            $total_expense = Expense::whereDate('date',$row->toDateString())->latest();
            $no_of_expense = Expense::whereDate('date',$row->toDateString())->latest()->count();
            $no_of_invoices1 = $no_of_invoices->count();
            $total_sales1 = $total_sales->sum('total');
            $total_expense1 = $total_expense->sum('amount');
            $this->gross_expense += $total_expense1;
            $this->gross_sales += $total_sales1;
            if($no_of_expense!= 0 || $total_expense1 != 0  )
            {
                $items = [
                    'no'  => $no_of_expense,
                    'expense'   => getFormattedCurrency($total_expense1)
                ];
                $this->expensecollect[$row->format('d/m/Y')] = $items;
            }
            if($no_of_invoices1!= 0 || $total_sales1 != 0  )
            {
                $items = [
                    'invoices'  => $no_of_invoices1,
                    'sales' => getFormattedCurrency($total_sales1),
                    'expense'   => getFormattedCurrency($total_expense1)
                ];
                $this->mycollection[$row->format('d/m/Y')] = $items;
            }
        }
        $this->net_income = $this->gross_sales - $this->gross_expense;
        return view('livewire.admin.reports.income-report');
    }

    //set default start dates,end dates & branch
    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
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
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $branch = $this->branch;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.income-report', compact('start_date', 'end_date','branch'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "IncomeReport_from_" . $start_date . ".pdf");
    }
}