<?php

namespace App\Http\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use App\Models\CustomerPaymentDiscount;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use App\Models\Translation;
use Illuminate\Support\Facades\Auth;

class CustomerView extends Component
{
    public $customer,$customer_id,$is_active,$invoices;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    //Render the page
    public function render()
    {
        return view('livewire.admin.customers.customer-view');
    }

    //Load viewing customer & their invoices
    public function mount($id){
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
        $this->customer_id = $id;
        $this->invoices = new Collection();
        $this->loadOrders();
    }

    //Load customer invoices
    public function loadOrders()
    {
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $myorder = $this->filterdata();
        $this->invoices->push(...$myorder->items());
        if ($this->hasMorePages = $myorder->hasMorePages()) {
            $this->nextCursor = $myorder->nextCursor()->encode();
        }
        $this->currentCursor = $myorder->cursor();
    }

    //reload customer invoices
    public function reloadOrders()
    {
        $this->invoices = new Collection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->invoices->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    //returns invoices that is for this customer
    public function filterdata()
    {
        $invoice = Invoice::where('customer_id',$this->customer_id)->latest();
        $invoices  = $invoice->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $invoices;
    }
}