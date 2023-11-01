<?php

namespace App\Http\Livewire\Admin\Payments;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Translation;

class AddPayment extends Component
{
    public $disable= true,$customers,$customer_query,$selected_customer,$deduct_type=1,$customer_id,$openingdisable = true,$invoicedisable = true;
    public $reference_number,$amount,$date,$payment_mode;
    public $total,$paid,$opening_balance,$discount,$balance,$customer,$opening_balance_received,$invoice_received,$myopenbal,$invoicebalance;
    public $invoice_paid,$prevent=false;

    //render the page
    public function render()
    {
        return view('livewire.admin.payments.add-payment');
    }

    // Handle changes in the customer query input.
    public function updatedCustomerQuery($value)
    {
        if($value != '')
        {
            $query2 = Customer::latest();
            /* if the user is branch */
            $query2->where('created_by',Auth::user()->id);
            $this->customers = $query2->where(function($query) use ($value){
                $query->where('file_number','like',$value.'%');
                $query->orwhere('first_name','like','%'.$value.'%');
                $query->orWhere('phone_number_1','like',$value.'%');
                $query->orWhere('phone_number_2','like',$value.'%');
               
            })
            ->where('is_active',1)->reorder()->orderby('file_number','ASC')->limit(8)->get();
        }
        else{
            $this->customers = null;
        }
    }

    //select the customer from dropdown, then prefill all data
    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::where('is_active',1)->where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = null;
        if($this->selected_customer) 
        {
            $this->customer_id = $this->selected_customer->id;
            $this->total = \App\Models\Invoice::where('customer_id',$this->customer_id)->sum('total');
            $this->paid = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->sum('paid_amount');
            $this->invoice_paid = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->where('payment_type',1)->sum('paid_amount');
            $open = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->where('payment_type',2)->sum('paid_amount');
            $this->invoicebalance = $this->total - $this->invoice_paid;
            $this->myopenbal = $this->selected_customer->opening_balance;
            $this->opening_balance_received = $open;
            $this->opening_balance = $this->selected_customer->opening_balance - $open;
            $this->discount = \App\Models\CustomerPaymentDiscount::where('customer_id',$this->customer_id)->sum('amount');
            $this->balance = ($this->total+$this->myopenbal)-($this->paid);
            $this->disable = false;
            if( $this->invoice_paid  >= $this->total)
            {
                $this->invoicedisable = true;
            }
            else{
                $this->invoicedisable = false;
            }
            if($open >= $this->selected_customer->opening_balance)
            {
                $this->openingdisable = true;
                $this->deduct_type = 2;
            }
            else{
                $this->openingdisable = false;
            }
            if($this->openingdisable == true && $this->invoicedisable == true)
            {
                $this->deduct_type =3;
            }
        }
    }

    //load default settings
    public function mount(){
        $this->date = \Carbon\Carbon::now()->toDateString();
    }

    //save the payment
    public function save()
    {
        if($this->prevent == true && $this->deduct_type != 3)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Check Error Messages!']);
            return 1;
        }
        if(!$this->selected_customer)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Customer is not selected!']);
            return 1;   
        }
        $this->validate([
            'date'  => 'required',
            'amount'    => 'required',
            'payment_mode'  => 'required'
        ]);
        if($this->deduct_type == 3)
        {
            $this->saveLedgerPayment();
            return 1;
        }
        $total = \App\Models\Invoice::where('customer_id',$this->selected_customer->id)->sum('total');
        $paid = \App\Models\InvoicePayment::where('customer_id',$this->selected_customer->id)->sum('paid_amount');
        $opening_balance = $this->selected_customer->opening_balance!=''?$this->selected_customer->opening_balance:0;
        $discount = \App\Models\CustomerPaymentDiscount::where('customer_id',$this->selected_customer->id)->sum('amount');
        $balance = ($total+$opening_balance)-($paid);
        if($this->amount <= 0) 
        {
            $this->addError('amount','Amount Cannot be less than 0.');
            return false;
        }
        if($this->deduct_type == 1)
        {
            if($this->amount > $this->opening_balance)
            {
                $this->addError('amount','Customer has already paid the opening balance');
                return 1;
            }
            InvoicePayment::create([
                'date' => Carbon::parse($this->date)->setTimeFrom(Carbon::now()),
                'invoice_id'    => null,
                'customer_name' => $this->selected_customer->first_name,
                'customer_id'   => $this->selected_customer->id,
                'created_by'    => Auth::user()->id,
                'financial_year_id' => getFinancialYearID(),
                'branch_id' => Auth::user()->id,
                'payment_mode'  => $this->payment_mode,
                'paid_amount'   => $this->amount,
                'note'  => $this->reference_number,
                'payment_type'  => 2
            ]);
        }
        if($this->deduct_type == 2)
        {
            $invoicebalance = 0;
            $customerinvoice = Invoice::where('customer_id',$this->selected_customer->id)->count();
            $invoiceamount = Invoice::where('customer_id',$this->selected_customer->id)->sum('total');
            $invoice_paid = InvoicePayment::where('customer_id',$this->selected_customer->id)->where('payment_type',1)->sum('paid_amount');
            $invbal = $invoiceamount - $invoice_paid;
            if($this->amount > $invbal)
            {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'message' => 'Amount cannot be greater than remaining amount!']);
                return false;
            }
            if($customerinvoice == 0)
            {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'message' => 'Cannot add payment because customer has no invoices!']);
                return false;
            }
            if($invbal <= 0)
            {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'message' => 'Cannot add payment because customer has no pending amount!']);
                return false;
            }
            $invoices = Invoice::where('customer_id',$this->selected_customer->id)->orderBy('created_at','ASC')->get();
            foreach($invoices as $row)
            {
                $localinvoicepaid = InvoicePayment::where('invoice_id',$row->id)->sum('paid_amount');
                if($localinvoicepaid < $row->total && !$this->amount <= 0 )
                {
                    $order_remain = $row->total - $localinvoicepaid;
                    if($this->amount  >= $order_remain)
                    {
                        $payable =  $order_remain;
                        InvoicePayment::create([
                            'date' =>Carbon::parse($this->date)->setTimeFrom(Carbon::now()),
                            'invoice_id'    => $row->id,
                            'customer_name' => $this->selected_customer->first_name,
                            'customer_id'   => $this->selected_customer->id,
                            'created_by'    => Auth::user()->id,
                            'financial_year_id' => getFinancialYearID(),
                            'branch_id' => Auth::user()->id,
                            'payment_mode'  => $this->payment_mode,
                            'paid_amount'   => $payable,
                            'note'  => $this->reference_number,
                            'payment_type'  => 1
                        ]);
                        $this->amount = $this->amount - $payable;
                    }
                    else{
                        $payable =  $this->amount;
                        InvoicePayment::create([
                            'date' => Carbon::parse($this->date)->setTimeFrom(Carbon::now()),
                            'invoice_id'    => $row->id,
                            'customer_name' => $this->selected_customer->first_name,
                            'customer_id'   => $this->selected_customer->id,
                            'created_by'    => Auth::user()->id,
                            'financial_year_id' => getFinancialYearID(),
                            'branch_id' => Auth::user()->id,
                            'payment_mode'  => $this->payment_mode,
                            'paid_amount'   => $payable,
                            'note'  => $this->reference_number,
                            'payment_type'  => 1
                        ]);
                        $this->amount = $this->amount - $payable;
                    }
                }
            }
        }
        $this->amount = 0;
        $this->selected_customer->refresh();
        $this->total = \App\Models\Invoice::where('customer_id',$this->customer_id)->sum('total');
        $this->paid = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->sum('paid_amount');
        $this->invoice_paid = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->where('payment_type',1)->sum('paid_amount');
        $open = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->where('payment_type',2)->sum('paid_amount');
        $this->myopenbal = $this->selected_customer->opening_balance;
        $this->opening_balance_received = $open;
        $this->opening_balance = $this->selected_customer->opening_balance - $open;
        $this->discount = \App\Models\CustomerPaymentDiscount::where('customer_id',$this->customer_id)->sum('amount');
        $this->balance = ($this->total+$this->myopenbal)-($this->paid);
        if( $this->invoice_paid  >= $this->total)
        {
            $this->invoicedisable = true;
        }
        else{
            $this->invoicedisable = false;
        }
        if($open >= $this->selected_customer->opening_balance)
        {
            $this->openingdisable = true;
            $this->deduct_type = 2;
        }
        else{
            $this->openingdisable = false;
        }
        if($this->openingdisable == true && $this->invoicedisable == true)
            {
                $this->deduct_type =3;
            }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Payment Has Been Added!']);
        return 1;   
    }

    //update amount balance,amount after payment value change
    public function updatedAmount($value)
    {
        $this->recalculate();
    }

    //update amount balance,amount after payment value change
    public function updatedDeductType()
    {
        $this->recalculate();
    }

    //recalculate balance and paid amonths
    public function recalculate()
    {
        $this->clearValidation('amount');
        if($this->deduct_type == 1)
        {
            if($this->amount > $this->opening_balance)
            {
                $this->addError('amount','Amount cannot be greater than remaining opening balance!');
                $this->prevent = true;
            }
            else{
                $this->resetErrorBag();
                $this->prevent = false;
            }
        }
        elseif($this->deduct_type == 2){
            $customerinvoice = Invoice::where('customer_id',$this->selected_customer->id)->count();
            $invoiceamount = Invoice::where('customer_id',$this->selected_customer->id)->sum('total');
            $invoice_paid = InvoicePayment::where('customer_id',$this->selected_customer->id)->where('payment_type',1)->sum('paid_amount');
            $invbal = $invoiceamount - $invoice_paid;
            if($this->amount > $invbal)
            {
                $this->addError('amount','Amount cannot be greater than remaining invoice balance!');
                $this->prevent = true;
            }
            else{
                $this->resetErrorBag();
                $this->prevent = false;
            }
        }
        else{
            $this->prevent = false;
        }
    }

    //save payments
    public function saveLedgerPayment()
    {
        InvoicePayment::create([
            'date' => Carbon::parse($this->date)->setTimeFrom(Carbon::now()),
            'invoice_id'    => null,
            'customer_name' => $this->selected_customer->first_name,
            'customer_id'   => $this->selected_customer->id,
            'created_by'    => Auth::user()->id,
            'financial_year_id' => getFinancialYearID(),
            'branch_id' => Auth::user()->id,
            'payment_mode'  => $this->payment_mode,
            'paid_amount'   => $this->amount,
            'note'  => $this->reference_number,
            'payment_type'  => 3
        ]);
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Payment Has Been Added!']);
            $this->amount = 0;
            $this->selected_customer->refresh();
            $this->total = \App\Models\Invoice::where('customer_id',$this->customer_id)->sum('total');
            $this->paid = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->sum('paid_amount');
            $this->invoice_paid = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->where('payment_type',1)->sum('paid_amount');
            $open = \App\Models\InvoicePayment::where('customer_id',$this->customer_id)->where('payment_type',2)->sum('paid_amount');
            $this->myopenbal = $this->selected_customer->opening_balance;
            $this->opening_balance_received = $open;
            $this->opening_balance = $this->selected_customer->opening_balance - $open;
            $this->discount = \App\Models\CustomerPaymentDiscount::where('customer_id',$this->customer_id)->sum('amount');
            $this->balance = ($this->total+$this->myopenbal)-($this->paid);
            if( $this->invoice_paid  >= $this->total)
            {
                $this->invoicedisable = true;
            }
            else{
                $this->invoicedisable = false;
    
            }
            if($open >= $this->selected_customer->opening_balance)
            {
                $this->openingdisable = true;
            }
            else{
                $this->openingdisable = false;
            }
            $this->deduct_type =3;
            return 1;   
    }
}