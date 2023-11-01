<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\Purchase;
use Carbon\Carbon;
use Livewire\Component;

class PrintPurchaseReport extends Component
{
    //render the page
    public function render()
    {
        return view('livewire..admin.reports.prints.print-purchase-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date ,$end_date)
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
        $this->purchases = Purchase::whereDate('purchase_date','>=',$this->start_date)->whereDate('purchase_date','<=',$this->end_date)->where('purchase_type',2)->latest()->get();
    }
}