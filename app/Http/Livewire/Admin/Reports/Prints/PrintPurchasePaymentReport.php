<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\SupplierPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class PrintPurchasePaymentReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$payments,$payment_mode='',$recvia='';

    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-purchase-payment-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date ,$end_date,$recvia=null)
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
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
        $payments = SupplierPayment::whereDate('created_at','>=',$this->start_date)->whereDate('created_at','<=',$this->end_date)->latest();
        if($this->payment_mode != '')
        {
            $payments->where('payment_mode',$this->payment_mode);
        }
        $this->payments = $payments->get();
    }
}