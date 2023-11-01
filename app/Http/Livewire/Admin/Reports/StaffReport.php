<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Models\Invoice;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class StaffReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$invoices,$staffs,$staff;
    public function render()
    {
        $invoices = Invoice::whereDate('date','>=',$this->start_date)->whereDate('date','<=',$this->end_date)->latest()->whereNotNull('salesman_id');
        if($this->branch != '')
        {
            $invoices->where('created_by',$this->branch);
        }
        if($this->staff != '')
        {
            $invoices->where('salesman_id',$this->staff);
        }
        else{
            $staff_ids = User::whereIn('user_type',[4])->whereBranchId(Auth::user()->id)->pluck('id');
            $invoices->whereIn('salesman_id',$staff_ids);
        }
        $this->invoices = $invoices->get();
        return view('livewire.admin.reports.staff-report');
    }

    //set default start dates,end dates,
    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
        /* if the user is branch */
        $this->branches = User::whereIn('user_type',[3,2])->get();
        $this->staffs = User::whereIn('user_type',[4])->whereBranchId(Auth::user()->id)->get();
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
        $staff = $this->staff;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.staff-report', compact('start_date', 'end_date','staff'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "StaffReport_from_" . $start_date . ".pdf");
    }
}
