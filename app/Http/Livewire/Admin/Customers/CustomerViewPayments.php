<?php

namespace App\Http\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Auth;
use App\Models\Translation;

class CustomerViewPayments extends Component
{
    public $customer_id,$customer,$payments;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    //render the page
    public function render()
    {
        return view('livewire.admin.customers.customer-view-payments');
    }

    //load payments
    public function mount($id)
    {
        $this->customer_id = $id;
        if(Auth::user()->user_type==2) {
            $this->customer=Customer::find($id);
       }
       /* if the user is branch */
       if(Auth::user()->user_type==3) {
            $this->customer=Customer::where('created_by',Auth::user()->id)->where('id',$id)->first();
        }
        if(!$this->customer)
        {
            abort(404);
        }
        $this->payments = new Collection();
        $this->loadPayments();
    }

    //load payments and filter it.
    public function loadPayments()
    {
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $myorder = $this->filterdata();
        $this->payments->push(...$myorder->items());
        if ($this->hasMorePages = $myorder->hasMorePages()) {
            $this->nextCursor = $myorder->nextCursor()->encode();
        }
        $this->currentCursor = $myorder->cursor();
    }

    //reload payments
    public function reloadPayments()
    {
        $this->payments = new Collection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->payments->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    //filter payments so that only the customer's payments are shown
    public function filterdata()
    {
        $payment = InvoicePayment::where('customer_id',$this->customer_id)->orderBy('id','desc')->orderBy('date','desc');
        $payments  =  $payment->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $payments;
    }
}