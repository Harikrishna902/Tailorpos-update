<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\Invoice;
use App\Models\SalesReturn;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReturnReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$salesreturns;

    //render the page & data
    public function render()
    {
        $salesreturns = SalesReturn::whereDate('date','>=',$this->start_date)->whereDate('date','<=',$this->end_date)->latest();
        if($this->branch != '')
        {
            $salesreturns->where('created_by',$this->branch);
        }
        $this->salesreturns = $salesreturns->get();
        return view('livewire.admin.reports.sales-return-report');
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
           $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.sales-return-report', compact('start_date', 'end_date','branch'))->output();
           return response()->streamDownload(fn () => print($pdfContent), "SalesReturnReport_from_" . $start_date . ".pdf");
       }
}