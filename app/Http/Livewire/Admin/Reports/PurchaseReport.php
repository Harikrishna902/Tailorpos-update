<?php

namespace App\Http\Livewire\Admin\Reports;


use App\Models\Purchase;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$purchases;
    
    //render the page & data
    public function render()
    {
        $purchases = Purchase::whereDate('purchase_date','>=',$this->start_date)->whereDate('purchase_date','<=',$this->end_date)->where('purchase_type',2)->latest();
        $this->purchases = $purchases->get();
        return view('livewire.admin.reports.purchase-report');
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
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.purchase-report', compact('start_date', 'end_date','branch'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "PurchaseReport_from_" . $start_date . ".pdf");
    }
}