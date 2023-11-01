<?php

namespace App\Http\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Translation;

class CustomerEdit extends Component
{
    public $date, $file_number, $first_name, $second_name, $family_name, $phone_number_1, $phone_number_2, $address, $customer_group_id, $edit_id;
    public $notes, $created_by, $opening_balance, $is_active = 1, $email;

    //Render the page
    public function render()
    {
        return view('livewire.admin.customers.customer-edit');
    }

    /* set value at the time of render */
    public function mount($id)
    {
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
        $this->date = \Carbon\Carbon::today()->toDateString();
        $customer = Customer::find($id);
        if(Auth::user()->id != $customer->created_by)
        {
            abort(404);
        }
        $this->first_name = $customer->first_name;
        $this->second_name = $customer->second_name;
        $this->family_name = $customer->family_name;
        $this->file_number = $customer->file_number;
        $this->address = $customer->address;
        $this->phone_number_1 = $customer->phone_number_1;
        $this->phone_number_2 = $customer->phone_number_2;
        $this->customer_group_id = $customer->customer_group_id;
        $this->is_active = $customer->is_active;
        $this->opening_balance = $customer->opening_balance;
        $this->notes = $customer->notes;
        $this->email = $customer->email;
        $this->edit_id = $id;
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
                Rule::unique('customers')->ignore($this->edit_id)
                    ->where('created_by', Auth::user()->id)
            ],
        ]);
        $customer = Customer::updateOrCreate(['id' => $this->edit_id], [
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
            'opening_balance' => ($this->opening_balance != "") ? $this->opening_balance : 0,
            'customer_group_id' => ($this->customer_group_id != "") ? $this->customer_group_id : NULL,
        ]);
        $this->emit('savemessage', ['type' => 'success', 'title' => 'Success', 'message' => 'Customer Created Successfully!']);
        return redirect()->route('admin.customers');
    }
}