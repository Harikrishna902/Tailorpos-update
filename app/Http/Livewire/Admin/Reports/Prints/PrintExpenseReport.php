<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintExpenseReport extends Component
{
    public $start_date,$end_date,$branch,$branches,$payment_mode;
    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-expense-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date ,$end_date,$recvia=null,$branch='')
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
        $this->branches = User::whereIn('user_type',[3,2])->get();
        $this->branch = $branch;
        if($recvia == 'all')
        {
            $recvia = '';
        }
        /* if the user is not admin */
        if(Auth::user()->user_type != 2)
        {
            $this->branch = Auth::user()->id;
        }
        $this->payment_mode = $recvia;
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
    }
}