<?php

namespace App\Http\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use Auth;
use Illuminate\Validation\Rule;
use App\Models\Translation;

class CustomerCreate extends Component
{
    public $date, $file_number, $first_name, $second_name, $family_name, $phone_number_1, $phone_number_2, $address, $customer_group_id;
    public $notes, $created_by, $opening_balance, $is_active = 1, $email;

    //Render the page
    public function render()
    {
        return view('livewire.admin.customers.customer-create');
    }

    /* set value at the time of render */
    public function mount()
    {
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
        $this->date = \Carbon\Carbon::today()->toDateString();
    }

    /* save the customer details */
    public function save()
    {
        $this->validate([
            'first_name' => 'required',
            'phone_number_1' => 'required',
            'opening_balance' => 'nullable|numeric',
            'email' => 'nullable|email',
            'file_number' =>  [
                'numeric',
                'required',
                Rule::unique('customers')
                    ->where('created_by', Auth::user()->id)
            ],
        ]);
        $user = Auth::user();
        $customer = Customer::create([
            'date' =>  $this->date,
            'file_number' => $this->file_number,
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'family_name' => $this->family_name,
            'phone_number_1' => $this->phone_number_1,
            'phone_number_2' => $this->phone_number_2,
            'is_active' => $this->is_active ?? 0,
            'address' => $this->address,
            'notes' => $this->notes,
            'email' => $this->email,
            'created_by' => Auth::user()->id,
            'opening_balance' => ($this->opening_balance != "") ? $this->opening_balance : 0,
            'customer_group_id' => ($this->customer_group_id != "") ? $this->customer_group_id : NULL,
        ]);
        $this->emit('savemessage', ['type' => 'success', 'title' => 'Success', 'message' => 'Customer Created Successfully!']);
        return redirect()->route('admin.customers');
    }
}