<?php

namespace App\Http\Livewire\Admin\OnlineOrders;

use Livewire\Component;
use App\Models\OnlineOrder;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Auth;

class OnlineOrders extends Component
{
    public $orders,$search;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    public function render()
    {
        return view('livewire.admin.online-orders.online-orders');
    }


    //Load order data
    public function mount()
    {
        $this->orders = new Collection;
        $this->loadOrders();
    }

    //Reload orders on search field is changed
    public function updatedSearch($value)
    {
        $this->reloadOrders();
    }

    //load orders into variable
    public function loadOrders()
    {
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->orders->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    //Empty variable and load orders again
    public function reloadOrders()
    {
        $this->orders = new Collection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->orders->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    //Filter data
    public function filterdata()
    {
        $query = OnlineOrder::latest();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query->where('branch_id',Auth::user()->id);
        }
        if($this->search != '')
        {
            $value = $this->search;
            $query->where(function ($query2) use ($value){
                $query2->where('customer_name', 'like', $value . '%')
                ->orwhere('order_number', 'LIKE',  '%'.$value .'%');
            });
            $query->reorder()->orderby('created_at','desc');
        }
        $orders  = $query->cursorPaginate(30, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $orders;
    }
}
