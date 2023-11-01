<?php

namespace App\Http\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\CustomerPaymentDiscount;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Auth;
use App\Models\Translation;

class CustomerViewPaymentDiscount extends Component
{
    public $customer_id, $discounts, $discount_date, $discount_amount, $discount, $customer;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    protected $listeners = ['resetPage' => 'resetPage'];

    //render the page
    public function render()
    {
        return view('livewire.admin.customers.customer-view-payment-discount');
    }

    //load customer data & customer's payment discounts
    public function mount($id)
    {
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
        $this->customer_id = $id;
        if (Auth::user()->user_type == 2) {
            $this->customer = Customer::find($id);
        }
        /* if the customer is branch */
        if (Auth::user()->user_type == 3) {
            $this->customer = Customer::where('created_by', Auth::user()->id)->where('id', $id)->first();
        }
        if (!$this->customer) {
            abort(404);
        }
        $this->discounts = new Collection();
        $this->loadDiscounts();
    }

    //prepare customer discount eidt
    public function edit($id)
    {
        $this->discount = CustomerPaymentDiscount::find($id);
        $this->discount_date = $this->discount->date;
        $this->discount_amount = $this->discount->amount;
    }

    //reset input fields
    public function resetDiscount()
    {
        $this->discount_date = \Carbon\Carbon::now()->toDateString();
        $this->discount_amount = "";
    }

    //reload discounts 
    public function resetPage()
    {
        $this->discounts = CustomerPaymentDiscount::where('customer_id', $this->customer_id)->latest()->get();
    }

    //create payment discount
    public function addDiscount()
    {
        $this->validate([
            'discount_date'  => 'required',
            'discount_amount' => 'required|numeric',
        ]);
        $total = \App\Models\Invoice::where('customer_id', $this->customer->id)->sum('total');
        $paid = \App\Models\InvoicePayment::where('customer_id', $this->customer->id)->sum('paid_amount');
        $opening_balance = $this->customer->opening_balance != '' ? $this->customer->opening_balance : 0;
        $discount = \App\Models\CustomerPaymentDiscount::where('customer_id', $this->customer->id)->sum('amount');
        $balance = ($total + $opening_balance) - ($discount + $paid);
        if ($this->discount_amount > $balance) {
            $this->addError('discount', 'Discount Cannot be greater than Balance.');
            return false;
        }
        $this->discount->date = $this->discount_date;
        $this->discount->amount = $this->discount_amount;
        $this->discount->save();
        $this->discounts = CustomerPaymentDiscount::where('customer_id', $this->customer_id)->latest()->get();
        $this->resetDiscount();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success',  'message' => 'Discount Edited Successfully!']
        );
    }

    //delete payment discount
    public function delete()
    {
        if ($this->discount) {
            $this->discount->delete();
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'success',  'message' => 'Discount has been deleted!']
            );
            $this->discount = null;
            $this->emit('closemodal');
            $this->emit('resetPage');
            $this->discounts = CustomerPaymentDiscount::where('customer_id', $this->customer_id)->latest()->get();
        }
    }

    //confirm delete
    public function confirmDelete($id)
    {
        $this->discount = CustomerPaymentDiscount::find($id);
    }

    //load discounts
    public function loadDiscounts()
    {
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $myorder = $this->filterdata();
        $this->discounts->push(...$myorder->items());
        if ($this->hasMorePages = $myorder->hasMorePages()) {
            $this->nextCursor = $myorder->nextCursor()->encode();
        }
        $this->currentCursor = $myorder->cursor();
    }

    //reload discounts
    public function reloadDiscounts()
    {
        $this->discounts = new Collection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->discounts->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    //filter discounts
    public function filterdata()
    {
        $invoice = CustomerPaymentDiscount::where('customer_id', $this->customer_id)->latest();
        $invoices  = $invoice->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $invoices;
    }
}