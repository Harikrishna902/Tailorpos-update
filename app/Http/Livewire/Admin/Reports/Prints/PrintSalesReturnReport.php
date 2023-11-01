<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\SalesReturn;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintSalesReturnReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$salesreturns;

    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-sales-return-report')
                    ->extends('layouts.print-layout')
                    ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date ,$end_date,$branch=null)
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
        $this->branches = User::whereIn('user_type',[3,2])->get();
        $salesreturns = SalesReturn::whereDate('date','>=',$this->start_date)->whereDate('date','<=',$this->end_date)->latest();
        /* if the user is not admin */
        if(Auth::user()->user_type != 2)
        {
            $this->branch = Auth::user()->id;
        }
        if($this->branch != '')
        {
            $salesreturns->where('created_by',$this->branch);
        }
        $this->salesreturns = $salesreturns->get();
        /* if the user is not admin */
        if(Auth::user()->user_type != 2)
        {
            $this->branch = Auth::user()->id;
        }
    }
}