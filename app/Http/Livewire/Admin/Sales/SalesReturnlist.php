<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\SalesReturn;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Pagination\Cursor;

class SalesReturnlist extends Component
{
    public $invoices, $invoice, $pay_mode, $paid_amount, $reference, $search = '', $remaining = 0, $mypaid, $balance;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    //render the page
    public function render()
    {
        return view('livewire.admin.sales.sales-returnlist');
    }

    //load invoices
    public function mount()
    {
        $this->invoices = new Collection;
        $this->loadOrders();
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    //reload list when search is updated
    public function updatedSearch($value)
    {
        $this->reloadOrders();
    }

    //check if financial year id is set
    public function check()
    {
        if (getFinancialYearID() == null) {
            $this->dispatchBrowserEvent(
                'swal-alert',
                ['type' => 'error', 'title' => 'Financial Year Not Set!',  'message' => 'Contact an admin!']
            );
            return 1;
        }
        return redirect()->route('admin.invoice');
    }

    /* refresh the page */
    public function refresh()
    {
        /* if search query or order filter is empty */
        if ($this->search == '') {
            $this->orders->fresh();
        }
    }

    //load orders
    public function loadOrders()
    {
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $myorder = $this->filterdata();
        $this->invoices->push(...$myorder->items());
        if ($this->hasMorePages = $myorder->hasMorePages()) {
            $this->nextCursor = $myorder->nextCursor()->encode();
        }
        $this->currentCursor = $myorder->cursor();
    }

    //reload orders
    public function reloadOrders()
    {
        $this->invoices = new Collection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->invoices->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    //query data based on the filters
    public function filterdata()
    {
        $invoice = SalesReturn::latest();
        /* if the user is branch */
        if (Auth::user()->user_type == 3) {
            $invoice->where('created_by', Auth::user()->id);
        }
        if ($this->search != '') {
            $search = $this->search;
            $invoice->where(function ($query2) use ($search) {
                $query2->Where('invoice_number', 'like', '%' . $search . '%');
                $query2->orwhere('customer_name', 'like', '%' . $search . '%');
                $query2->orWhere('customer_file_number', 'like', $search . '%');
                $query2->orWhere('customer_phone', 'like', $search . '%');
            });
        }
        $invoices  = $invoice->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $invoices;
    }
}