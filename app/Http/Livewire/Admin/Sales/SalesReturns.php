<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\Product;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetail;
use App\Models\SalesReturnPayment;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
class SalesReturns extends Component
{
    public $selected_customer,$customer_query,$customer_results,$selected_invoice,$invoice_results,$invoice_query,$product_results=[],$cashpaid=null;
    public $products_query,$code;
    public $inputi,$selected_product=[],$quantityarray=[],$cart=[];
    public $existingquantityarray = [],$discount,$taxable,$taxamount,$tax=0,$subtotal,$discount_type,$total;

    //render the page
    public function render()
    {
        return view('livewire.admin.sales.sales-returns');
    }

    //load default language and generate a purchase code
    public function mount()
    {
        $this->code = $this->generateCode();
    }

    //select customer
    public function selectCustomer($id)
    {
        $this->clearAll();
        $this->selected_customer = Customer::where('is_active',1)->where('id',$id)->first();
        $this->customer_query = '';
        $this->customer_results = null;
    }

    //select invoice
    public function selectInvoice($id)
    {
        if($this->cart && count($this->cart) > 0)
        {
            $this->clearAll('KEEP_CUSTOMER');
        }
        $this->selected_invoice = Invoice::where('id',$id)->where('customer_id',$this->selected_customer->id)->first();
        $this->tax = $this->selected_invoice->tax_percentage;
        $this->invoice_query = '';
        $this->invoice_results = null;
    }

    //when values are updated,if they are an empty string make it null.
    public function updated($name,$value)
    {
        if ( $value == '' ) data_set($this, $name, null);
        $this->calculateTotal();
    }

    //when updating customer query , list customer matching that query
    public function updatedCustomerQuery($value)
    {
        $this->products_query = '';
        $this->invoice_query = '';
        $this->product_results = [];
        $this->invoice_results = [];
        if($value != '')
        {
            $query2 = Customer::latest();
            /* if the user is branch */
            if(Auth::user()->user_type==3) {
                $query2->where('created_by',Auth::user()->id);
            }
            $this->customer_results = $query2->where(function($query) use ($value){
                $query->where('file_number','like',$value.'%');
                $query->orwhere('first_name','like','%'.$value.'%');
                $query->orWhere('phone_number_1','like',$value.'%');
                $query->orWhere('phone_number_2','like',$value.'%');
               
            })
            ->where('is_active',1)->reorder()->orderby('file_number','ASC')->limit(8)->get();
        }
        else{
            $this->customer_results = null;
        }
    }

    //add more rows
    public function addInput($i)
    {
        $this->inputi = $i+ 1;
        $this->selected_product[$this->inputi] = null; 
        $this->quantityarray[$this->inputi] = null; 
        $this->cart[$this->inputi] = ['product' => '23'];
    }

    //when updating invoice query
    public function updatedInvoiceQuery($value)
    {
        $this->products_query = '';
        $this->customer_query = '';
        $this->product_results = [];
        $this->customer_results = [];
        if($value != '' && $this->selected_customer)
        {
            $query2 = Invoice::latest();
            /* if the user is branch */
            if(Auth::user()->user_type==3) {
                $query2->where('created_by',Auth::user()->id);
            }
            $customer = $this->selected_customer->id;
            $this->invoice_results = $query2->where(function($query) use ($value,$customer){
                $query->where('invoice_number','like','%'.$value.'%');
            })
            ->limit(8)->where('customer_id','like','%'.$customer.'%')->get();
        }
        else{
            $this->invoice_results = null;
        }
    }

    //update product query when updating filters
    public function updatedProductsQuery($value)
    {
        $this->customer_query = '';
        $this->invoice_query = '';
        $this->customer_results = [];
        $this->invoice_results = [];
        if($value != '' && $value != '@' && $this->selected_invoice)
        {
            $currentitems = $this->cart;
            $currentitems = collect($this->cart)->pluck('id');
            $query2 = InvoiceDetail::latest()->whereNotIn('id',$currentitems)->where('invoice_id',$this->selected_invoice->id);
            $this->product_results = $query2->where(function($query) use ($value){
                $query->where('item_name','like','%'.$value.'%');
               
            }) ->limit(8)->get();
        }
        elseif($value == '@')
        {
            $currentitems = collect($this->cart)->pluck('id');
            $query2 = InvoiceDetail::latest()->whereNotIn('id',$currentitems)->where('invoice_id',$this->selected_invoice->id);
            $this->product_results = $query2->limit(8)->get();
        }
        else{
            $this->product_results = [];
        }
    }

    //select product
    public function selectProduct($id)
    {
        $product = InvoiceDetail::find($id);
        $this->cart[$id] = $product;
        $this->products_query = '';
        if($product->quantity >= 1)
        {
            $this->quantityarray[$id] = 1;
        }
        $this->product_results = [];
        $orderdetail = SalesReturnDetail::where('invoice_detail_id',$id)->get();
        $sum = $orderdetail->sum('quantity');
        $this->cart[$id]['original_quantity'] = $this->cart[$id]['quantity'];
        $this->cart[$id]['quantity'] = $this->cart[$id]['quantity'] - $sum;
        $this->calculateTotal();
    }

    //remove cart item
    public function removeItem($id)
    {
        unset($this->cart[$id]);
        unset($this->quantityarray[$id]);
        $this->calculateTotal();
    }

    //clear all cart item
    public function clearAll($options = null)
    {
        $this->cart = [];
        $this->quantityarray = [];
        if($options != 'KEEP_CUSTOMER')
        {
            $this->selected_customer = null;
        }
        $this->selected_invoice = null;
    }

    //calculate tax
    public function calculateTax($id)
    {
        $product = $this->cart[$id];
        $tax_percentage = $this->selected_invoice->tax_percentage;
        $tax_type = $this->selected_invoice->tax_type;
        $itemtaxtotal = 0;
        $itemtotal = 0;
        $itemtaxtotal2 = 0;
        $sub_total = 0;
        if($tax_type == 2)
        {
            $itemtotallocal =  ($product['rate'] * $this->quantityarray[$id])  * (100 / (100 + $tax_percentage ?? 15));
            $itemtaxtotal +=   ($product['rate'] * $this->quantityarray[$id]) - $itemtotallocal ?? 0;
            $itemtotal += ($product['rate'] * $this->quantityarray[$id]);
            $itemtaxtotal2 += $itemtaxtotal;
            $sub_total += $itemtotallocal;
        }
        else{
            $itemtotallocal =   ($product['rate'] * $this->quantityarray[$id]);
            $itemtaxtotal += $itemtotallocal * $this->tax/100;
            $itemtotal += $itemtotallocal+$itemtaxtotal;
            $itemtaxtotal2 += $itemtaxtotal;
        }
        return $itemtaxtotal2;
    }

    //calculate total
    public function calculateTotal()
    {
        if($this->selected_invoice)
        {
            $this->total = 0;
            $this->subtotal =0;
            $this->taxamount = 0;
            $this->taxable = 0;
            $unitprice =0;
            $itemtotal = 0;
            $itemtaxtotal2 = 0;
            $sub_total =0;
            foreach($this->cart as $key => $item)
            {
                $itemtaxtotal = 0;
                $product = $item;
                $itemtotallocal =  (($product['total'] / $product['original_quantity'])* $this->quantityarray[$key])  * (100 / (100 + $this->tax ?? 15));
                $itemtaxtotal +=  (($product['total'] / $product['original_quantity']) * $this->quantityarray[$key]) - $itemtotallocal ?? 0;
                $itemtotal +=( ($product['total'] / $product['original_quantity']) * $this->quantityarray[$key]);
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotal;
                $sub_total += $itemtotallocal;
            }
            $this->subtotal = $this->taxable;
            $this->taxable = $itemtotal -  $this->discount;
            $unitprice = $this->taxable  * (100 / (100 + $this->tax ?? 15));
            $this->taxable  = $unitprice;
            $this->taxamount =  ($itemtotal - $this->discount) - $unitprice;
            $this->total = $this->taxable + $this->taxamount;
            $this->subtotal = $sub_total;
        }
    }

    //save sales return
    public function save()
    {
        $this->calculateTotal();
        if(!$this->selected_customer)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Customer is not selected!']);
            return 1;   
        }
        if(!$this->selected_invoice)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Invoice is not selected!']);
            return 1;   
        }
        if($this->selected_invoice->customer_id != $this->selected_customer->id)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Invoice selected does not match!']);
            return 1;   
        }
        if(count($this->cart) == 0)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'No Products Are Selected!']);
            return 1;   
        }
        if($this->total <= 0)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Total Cannot be less than 0!']);
            return 1;   
        }
        if($this->discount > $this->selected_invoice->discount)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Discount cannot be greater than billed discount!']);
            return 1;   
        }
        if($this->discount < 0)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Discount cannot be less than 0!']);
            return 1;   
        }
        foreach($this->cart as $key => $item)
        {
            if(!isset($this->quantityarray[$key]) || $this->quantityarray[$key] == '' || $this->quantityarray[$key] < 0)
            {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'title' => 'Check Quantity!' , 'message' => 'Invalid quantity in one of the items <br> <b> Quantity cannot be empty or be less than zero! </b>']);
                return 1;  
            }
            if($this->quantityarray[$key] > $item['quantity'] )
            {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'title' => 'Check Quantity!' , 'message' => 'The return quantity is greater than billed quantity of an item!']);
                return 1;  
            }
        }
        $total = 0;
        $taxtotal = 0;
        $subtotal = 0;
        foreach($this->cart as $key => $item)
        {
            $tax = $this->selected_invoice->tax_percentage ?? 0;
            $itemtaxtotal = 0;
            $selling_price = $item['rate'];
            $localrate = 0;
            if($this->selected_invoice->tax_type == 2)
            {
                $selling_price = $item['total'] / $item['quantity'];
                $localrate =  ($selling_price )  * (100 / (100 + $tax ?? 15));
                $itemtotallocal =  ($selling_price * $this->quantityarray[$key])  * (100 / (100 + $tax ?? 15));
                $itemtaxtotal =  ($selling_price * $this->quantityarray[$key]) - $itemtotallocal ?? 0;
                $itemtotal =  $selling_price * $this->quantityarray[$key];
                $subtotal += $itemtotallocal;
                $taxtotal += $itemtaxtotal;
            }
            else{
                $itemtotallocal =  ($selling_price * $this->quantityarray[$key]);
                $localrate = $selling_price;
                $itemtaxtotal = $itemtotallocal * $tax/100;
                $itemtotal = $itemtotallocal+$itemtaxtotal;
                $subtotal += $itemtotallocal;
                $taxtotal += $itemtaxtotal;
            }
            $total += $itemtotal;
        }
        $salesreturn = SalesReturn::create([
            'date' => Carbon::now(),
            'sales_return_number'    => $this->generateCode(),
            'customer_name' => $this->selected_customer->first_name,
            'customer_phone'    => $this->selected_customer->phone_number_1,
            'customer_address'  => $this->selected_customer->address,
            'customer_file_number'  => $this->selected_customer->file_number,
            'customer_id'   => $this->selected_customer->id,
            'discount'   => $this->discount,
            'tax_type'  => $this->selected_invoice->tax_type,
            'sub_total' => $this->subtotal,
            'tax_percentage'    => $this->selected_invoice->tax_percentage,
            'tax_amount'    => $this->taxamount,
            'taxable_amount'    => $this->taxable,
            'total' => $this->total,
            'invoice_id'    => $this->selected_invoice->id,
            'created_by'    => Auth::user()->id,
            'financial_year_id' => getFinancialYearID(),
            'branch_id' => Auth::user()->id,
            'status' => 0,
        ]);
        foreach($this->cart as $key => $item)
        {
            $tax = $this->selected_invoice->tax_percentage ?? 0;
            $itemtaxtotal = 0;
            $selling_price = $item['rate'];
            $localrate = 0;
            if($this->selected_invoice->tax_type == 2)
            {
                $selling_price = $item['total'] / $item['original_quantity'];
                $localrate =  ($selling_price )  * (100 / (100 + $tax ?? 15));
                $itemtotallocal =  ($selling_price * $this->quantityarray[$key])  * (100 / (100 + $tax ?? 15));
                $itemtaxtotal =  ($selling_price * $this->quantityarray[$key]) - $itemtotallocal ?? 0;
                $itemtotal =  $selling_price * $this->quantityarray[$key];
                SalesReturnDetail::create([
                    'sales_return_id'    => $salesreturn->id,
                    'type'  => $item['type'],
                    'tax_amount'    => $itemtaxtotal,
                    'quantity'  => $this->quantityarray[$key],
                    'item_id' => $item['item_id'],
                    'item_name'=>$item['item_name'],
                    'rate' => $item['rate'],
                    'total' => $itemtotal,
                    'unit_type' => $item['unit_type'],
                    'invoice_detail_id'   => $key,
                ]);
            }
            else{
                $itemtotallocal =  ($selling_price * $this->quantityarray[$key]);
                $localrate = $selling_price;
                $itemtaxtotal = $itemtotallocal * $tax/100;
                $itemtotal = $itemtotallocal+$itemtaxtotal;
                SalesReturnDetail::create([
                    'sales_return_id'    => $salesreturn->id,
                    'type'  => $item['type'],
                    'tax_amount'    => $itemtaxtotal,
                    'quantity'  => $this->quantityarray[$key],
                    'item_id' => $item['item_id'],
                    'item_name'=>$item['item_name'],
                    'rate' => $item['rate'],
                    'total' => $itemtotal,
                    'unit_type' => $item['unit_type'],
                    'invoice_detail_id'   => $key,
                ]);
            }
            $total += $itemtotal;
        }
        if($this->cashpaid && $this->cashpaid == 'true')
        {
            SalesReturnPayment::create([
                'date' => Carbon::now(),
                'invoice_id'    => $this->selected_invoice->id,
                'customer_name' => $this->selected_invoice->customer_name,
                'customer_id'   => $this->selected_invoice->customer_id,
                'created_by'    => Auth::user()->id,
                'financial_year_id' => getFinancialYearID(),
                'branch_id' => Auth::user()->id,
                'paid_amount'   => $this->total,
                'sales_return_id'   => $salesreturn->id,
                
            ]);
            $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Sales Return Created Successfully! & Payment was saved']);
            $this->emit('reloadpage');
            return 1;
        }
        $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Sales Return Created Successfully!']);
            $this->emit('reloadpage');
    }

    //generate sales return code
    public function generateCode()
    {
        $code_prefix='SR-';
        $ordernumber = SalesReturn::Orderby('id', 'desc')->latest()->first();
        /*if order number is exist*/
        if($ordernumber && $ordernumber->sales_return_number !=""){
            /* if invoice code not empty */
            $code=explode("-", $ordernumber->sales_return_number);
            $new_code = $code[1] + 1;
            $new_code = str_pad($new_code, 4, "0", STR_PAD_LEFT);
            return $code_prefix.$new_code;
        }else{
            /* if order code is empty set start */
            return $code_prefix.'0001';
        }
    }
}