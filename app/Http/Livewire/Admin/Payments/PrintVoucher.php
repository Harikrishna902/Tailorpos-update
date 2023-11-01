<?php

namespace App\Http\Livewire\Admin\Payments;

use Livewire\Component;
use App\Models\InvoicePayment;

class PrintVoucher extends Component
{
    public $bill;

    //render the page
    public function render()
    {
        return view('livewire.admin.payments.print-voucher')->extends('layouts.print-layout')
        ->section('content');
    }

    //load the bill
    public function mount($id){
        $this->bill = InvoicePayment::find($id);
        if(!($this->bill)) {
            abort(404);
        }
    }
}