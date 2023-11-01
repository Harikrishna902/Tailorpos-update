<?php

namespace App\Http\Livewire\Admin\OnlineCustomers;

use Livewire\Component;
use App\Models\OnlineCustomer;
use App\Models\CustomerGroup;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Auth;

class OnlineCustomers extends Component
{
    public $customers,$search;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    public function render()
    {
        return view('livewire.admin.online-customers.online-customers');
    }



    //Load customer data
    public function mount()
    {
        $this->customers = new Collection;
        $this->loadCustomers();
    }

    //Toggle customer active
    public function toggle($id)
    {
        $customer = OnlineCustomer::find($id);
        $customer->is_active = !($customer->is_active);
        $customer->save();
    }

    //Reload customers on search field is changed
    public function updatedSearch($value)
    {
        $this->reloadCustomers();
    }

    //load customers into variable
    public function loadCustomers()
    {
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $customers = $this->filterdata();
        $this->customers->push(...$customers->items());
        if ($this->hasMorePages = $customers->hasMorePages()) {
            $this->nextCursor = $customers->nextCursor()->encode();
        }
        $this->currentCursor = $customers->cursor();
    }

    //Empty variable and load customers again
    public function reloadCustomers()
    {
        $this->customers = new Collection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $customers = $this->filterdata();
        $this->customers->push(...$customers->items());
        if ($this->hasMorePages = $customers->hasMorePages()) {
            $this->nextCursor = $customers->nextCursor()->encode();
        }
        $this->currentCursor = $customers->cursor();
    }

    //Filter data
    public function filterdata()
    {
        $query = OnlineCustomer::latest();
        /* if the user is branch */
        
        if($this->search != '')
        {
            $value = $this->search;
            $query->where(function ($query2) use ($value){
                $query2->where('name', 'like', $value . '%')
                ->orwhere('phone', 'LIKE',  '%'.$value .'%')
                ->orWhere('email', 'like',  $value . '%');
            });
            $query->reorder()->orderby('created_at','desc');
        }
        $customers  = $query->cursorPaginate(30, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $customers;
    }
}
