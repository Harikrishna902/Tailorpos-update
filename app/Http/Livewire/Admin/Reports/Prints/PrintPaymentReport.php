<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintPaymentReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$invoices,$payment_mode='',$recvia='';

    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-payment-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date ,$end_date,$recvia=null,$branch='')
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
        $this->branches = User::whereIn('user_type',[3,2])->get();
        $this->branch = $branch;
        /* if the user is not admin */
        if(Auth::user()->user_type != 2)
        {
            $this->branch = Auth::user()->id;
        }
        if($recvia == 'all')
        {
            $recvia = '';
        }
        $this->payment_mode = $recvia;
        $payments = InvoicePayment::whereDate('date','>=',$this->start_date)->whereDate('date','<=',$this->end_date)->latest();
        if($this->branch != '')
        {
            $payments->where('created_by',$this->branch);
        }
        if($this->payment_mode != '')
        {
            $payments->where('payment_mode',$this->payment_mode);
        }
        $this->payments = $payments->orderBy('voucher_no','asc')->get();
    }
}