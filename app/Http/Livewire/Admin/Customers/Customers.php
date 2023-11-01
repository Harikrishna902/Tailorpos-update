<?php

namespace App\Http\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Auth;

class Customers extends Component
{
    public $customers,$search,$group_search,$groups;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    //Render the page
    public function render()
    {
        return view('livewire.admin.customers.customers');
    }

    //Load customer data
    public function mount()
    {
        if(Auth::user()->user_type==2) {
             $this->groups = CustomerGroup::where('is_active',1)->latest()->get();
        }
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
        $this->groups = CustomerGroup::where('is_active',1)->where('created_by',Auth::user()->id)->latest()->get();
        }
        $this->customers = new Collection;
        $this->loadCustomers();
    }

    //Toggle customer active
    public function toggle($id)
    {
        $customer = Customer::find($id);
        $customer->is_active = !($customer->is_active);
        $customer->save();
    }

    //Reload customers on search field is changed
    public function updatedSearch($value)
    {
        $this->reloadCustomers();
    }

    //reload customers on group is changed
    public function updatedgroupSearch($value)
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
        $query = Customer::latest();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query->where('created_by',Auth::user()->id);
        }
        if($this->search != '')
        {
            $value = $this->search;
            $query->where(function ($query2) use ($value){
                $query2->where('file_number', 'like', $value . '%')
                ->orwhere('first_name', 'LIKE',  '%'.$value .'%')
                ->orWhere('phone_number_1', 'like',  $value . '%')
                ->orWhere('phone_number_2', 'like',  $value . '%');
            });
            $query->reorder()->orderby('file_number','ASC');
        }
        if($this->group_search != '') 
        {
            $query->where('customer_group_id',$this->group_search);
        }
        $customers  = $query->cursorPaginate(30, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $customers;
    }
}