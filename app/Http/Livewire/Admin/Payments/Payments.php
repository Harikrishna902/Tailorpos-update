<?php

namespace App\Http\Livewire\Admin\Payments;

use App\Models\InvoicePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Translation;

class Payments extends Component
{
    public $payments,$payment,$search='',$pay_mode,$paid_amount,$reference;

    //render the page and get payments
    public function render()
    {
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $query = InvoicePayment::latest();
        }
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query = InvoicePayment::where('created_by',Auth::user()->id)->latest();
        }
        if($this->search != '')
        {
            $search = $this->search;
            $query->where(function($query2) use ($search){
                $query2->where('customer_name','like','%'.$search.'%');
                $query2->orwhere('voucher_no','like','%'.$search.'%');
                $query2->orwhereHas('customer',function($query3) use ($search)
                {
                    $query3->where('file_number','like',$search.'%');
                    $query3->orwhere('phone_number_1','like',$search.'%');
                    $query3->orwhere('phone_number_2','like',$search.'%');
                });
            });
        }
        $this->payments = $query->orderBy('voucher_no','asc')->get();
        return view('livewire.admin.payments.payments');
    }

    //prepare payment for edit
    public function edit($id)
    {
        $this->payment = InvoicePayment::find($id);
        $this->paid_amount = $this->payment->paid_amount;
        $this->pay_mode = $this->payment->payment_mode;
        $this->resetErrorBag();
    }

    //load payment for delete confirmation
    public function deleteConfirm($id)
    {
        $this->payment = InvoicePayment::find($id);
    }

    //delete payments
    public function delete()
    {
        if($this->payment)
        {
            $this->payment->delete();
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Payment Was Deleted!']);
            $this->emit('closemodal');
        }
    }

    //update payment
    public function update()
    {
        $this->validate([
            'paid_amount'   => 'required',
            'pay_mode'  => 'required'
        ]);
        if($this->payment && $this->payment->payment_type == 1)
        {
            $mypaid = InvoicePayment::where('invoice_id',$this->payment->invoice_id)->sum('paid_amount') - $this->payment->paid_amount;
            $balance = $this->payment->invoice->total - $mypaid;
            $paid_amount = $this->payment->invoice->total - $this->paid_amount;
            if($this->paid_amount > $balance)
            {
                $this->addError('paid_amount','Paid Amount Cannot Be Greater Than Balance Remaining!');
                return 1;
            }
            if($this->paid_amount < 0)
            {
                $this->addError('paid_amount','Paid Amount Cannot Be Less than 0!');
                return 1;
            }
            $this->payment->paid_amount = $this->paid_amount;
            $this->payment->payment_mode = $this->pay_mode;
            $this->payment->note = $this->reference;
            $this->payment->save();
        }
        if($this->payment && $this->payment->payment_type == 2)
        {
            $mypaid = InvoicePayment::where('customer_id',$this->payment->customer->id)->where('payment_type',2)->sum('paid_amount') - $this->payment->paid_amount;
            $balance = $this->payment->customer->opening_balance - $mypaid;
            $paid_amount = $this->payment->customer->opening_balance -  $this->paid_amount;
            if($this->paid_amount > $balance)
            {
                $this->addError('paid_amount','Paid Amount Cannot Be Greater Than Balance Remaining!');
                return 1;
            }
            if($this->paid_amount < 0)
            {
                $this->addError('paid_amount','Paid Amount Cannot Be Less than 0!');
                return 1;
            }
            $this->payment->paid_amount = $this->paid_amount;
            $this->payment->payment_mode = $this->pay_mode;
            $this->payment->note = $this->reference;
            $this->payment->save();
        }
        if($this->payment && $this->payment->payment_type == 3)
        {
            $this->payment->paid_amount = $this->paid_amount;
            $this->payment->payment_mode = $this->pay_mode;
            $this->payment->note = $this->reference;
            $this->payment->save();
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Payment Was Updated!']);
        $this->emit('closemodal');
        $this->payment = null;
    }
}