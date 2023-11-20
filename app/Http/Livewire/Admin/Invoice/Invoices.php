<?php

namespace App\Http\Livewire\Admin\Invoice;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\Material;
use App\Models\Product;
use App\Models\Translation;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Invoices extends Component
{
    public $products,$materials,$salesmen,$customers,$customer_results,$inv_id;
    public $material_query,$customer_query,$product_search;
    public $date,$invoice_number,$selected_customer,$selected_material,$selected_salesman,$discount,$notes,$paid_amount,$pay_mode,$reference;
    public $inputs,$prices=[],$cart_items=[],$material_items=[],$inputi,$rate=[],$quantity,$material_price=[],$mati,$matqty=[],$matrate=[];
    public $tax,$tax_type;
    public $material_rate,$material_qty;
    public $taxable,$taxamount,$sub_total,$total;
    public $file_number,$first_name,$second_name,$family_name,$phone_number_1,$phone_number_2,$address,$customer_group_id;
    public $notes_c,$created_by,$opening_balance,$is_active=1,$email,$discount_type;
    public $selling_price=[],$editkey,$stop=false;

    //render the page
    public function render()
    {
        $product_query = Product::where('is_active',1);
        if($this->product_search != '')
        {
            $product_query->where('name','like','%'.$this->product_search.'%');
        }
        $this->products  = $product_query->get();
        return view('livewire.admin.invoice.invoices');
    }

    // Initialization and setup logic for the component.
    public function mount()
    {
        /* if financial year id is null */
        if(getFinancialYearID() == null)
        {
            abort(403);
        }
        $this->invoice_number = generateInvoiceNumber();
        $this->date = Carbon::now()->toDateString();
        if(Auth::user()->user_type==2) {
            $query = User::whereIn('user_type',[4,5,6])->latest();
            $this->salesmen = User::where('user_type',4)->where('created_by',Auth::user()->id)->where('is_active',1)->get();
        }
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->salesmen = User::where('user_type',4)->where('created_by',Auth::user()->id)->where('is_active',1)->get();
        }
        /* if the user is salesman */
        if(Auth::user()->user_type==4) {
            $this->salesmen = Auth::user();
        }
        $this->tax = getTaxPercentage();
        $this->discount_type = getDiscountType();
    }
    
    // Handle changes in the customer query input.
    public function updatedCustomerQuery($value)
    {
        if($value != '')
        {
            $query2 = Customer::latest();
            $query2->where('created_by',Auth::user()->id);
            $query2->where(function($query) use ($value){
                $query->Where('file_number','like',$value.'%');
                $query->orwhere('first_name','like','%'.$value.'%');
                $query->orWhere('phone_number_1','like',$value.'%');
                $query->orWhere('phone_number_2','like',$value.'%');
               
            });
            $this->customer_results = $query2->where('is_active',1)->reorder()->orderby('file_number','ASC')->limit(8)->get();
        }
        else{
            $this->customer_results = null;
        }
    }

    // Handle changes in the material query input.
    public function updatedmaterialQuery($value)
    {
        if($value != '')
        {
            $this->materials = Material::where(function($query) use ($value){
                $query->where('name','like','%'.$value.'%');
            })
            ->where('is_active',1)->get();
        }
        else{
            $this->materials = null;
        }
    }

    // Select a customer based on the provided ID.
    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::where('is_active',1)->where('id',$id)->first();
        $this->customer_query = '';
        $this->customer_results = null;
    }

    // Add a product to the shopping cart.
    public function addToCart($id)
    {
        $product = Product::find($id);
        $this->addLocal($this->inputi);
        $this->cart_items[$this->inputi] = array([
            'product' => $id,
        ]);
        $tax_type = getTaxType();
        if($tax_type == 2)
        {
            $this->selling_price[$this->inputi] = $product->stitching_cost;
            $itemtotallocal =  $product->stitching_cost  * (100 / (100 + $this->tax ?? 15));
            $this->prices[$this->inputi] = number_format($itemtotallocal,2);
        }
        else{
            $this->selling_price[$this->inputi] = $product->stitching_cost;
            $this->prices[$this->inputi] = $product->stitching_cost;
        }
        $this->quantity[$this->inputi]  = 1;
        $this->calculateTotal();
    }

    // Increase the global index of products.
    public function addLocal($i)
    {
        $this->inputi = $i + 1;
        $this->inputs[$this->inputi] = 1;
        $this->cart_items[$this->inputi] = '';
        $this->quantity[$this->inputi]  = 1;
    }

    // Increase the global index of materials.
    public function addLocalM($i)
    {
        $this->mati = $i + 1;
    }

    /* increase the count */
    public function increase($key)
    {
        /* if quantity of key is exist */
        if(isset($this->quantity[$key] ))
        {
            $this->quantity[$key]++;
        }
        $this->calculateTotal();
    }

    /* decrease the count */
    public function decrease($key)
    {
        /* is quantity of key is exist */
        if(isset($this->quantity[$key] ))
        {
        if($this->quantity[$key] > 1)
        {
            /* if quantity of key is >1 */
            $this->quantity[$key]--;
        }
        }
        $this->calculateTotal();
    }

    // Remove an item from the cart.
    public function removeItem($key)
    {
        unset($this->quantity[$key]);
        unset($this->prices[$key]);
        unset($this->cart_items[$key]);
        $this->calculateTotal();
    }

    // Remove a material item.
    public function removeMat($key)
    {
        unset($this->matqty[$key]);
        unset($this->matrate[$key]);
        unset($this->material_items[$key]);
        $this->calculateTotal();
    }

    // Select a material based on the provided ID.
    public function selectMaterial($id)
    {
        $this->selected_material = Material::find($id);
        $this->materials = null;
        $this->material_query = '';
        $this->material_rate = $this->selected_material->price;
        $this->material_qty = 1;
    }

    // Add a material item to the cart.
    public function addMatToCart()
    {
        if(!$this->selected_material || $this->selected_material == null)
        {
            $this->addError('mat_error','Select A Material!');
            return false;
        }
        $this->validate([
            'material_rate' => 'required|numeric',
            'material_qty'  => 'required|numeric'
        ]);
        $this->addLocalM($this->mati);
        $this->matrate[$this->mati] = $this->material_rate;
        $this->material_items[$this->mati] = array([
            'product' => $this->selected_material->id,
        ]);
        $tax_type = getTaxType();
        $itemtotallocal = 0;
        if($tax_type == 2)
        {
            $itemtotallocal =  $this->material_rate ;
            $this->matrate[$this->mati] = number_format($itemtotallocal,2,'.','');
        }
        else{
            $this->matrate[$this->mati] = $this->material_rate ;
        }
        $this->matqty[$this->mati]  = $this->material_qty;
        $this->selected_material = null;
        $this->material_rate = null;
        $this->material_qty = null;
        $this->emit('closemodal');
        $this->calculateTotal();
    }

    // Calculate the total, subtotal, tax amount, and more.
    public function calculateTotal()
    {
        $this->total = 0;
        $this->sub_total =0;
        $this->taxamount = 0;
        $this->taxable = 0;
        $unitprice =0;
        $itemtotal = 0;
        $itemtaxtotal2 = 0;
        $sub_total =0;
        $tax_type = getTaxType();
        foreach($this->cart_items as $key => $item)
        {
            $itemtaxtotal = 0;
            $product = \App\Models\Product::find($item[0]['product']);
            if($tax_type == 2)
            {
                $itemtotallocal =  ($this->selling_price[$key] * $this->quantity[$key])  * (100 / (100 + $this->tax ?? 15));
                $itemtaxtotal +=  ($this->selling_price[$key] * $this->quantity[$key]) - $itemtotallocal ?? 0;
                $itemtotal +=( $this->selling_price[$key] * $this->quantity[$key]);
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotal;
                $sub_total += $itemtotallocal;
            }
            else{
                $itemtotallocal =  ($this->selling_price[$key] * $this->quantity[$key]);
                $itemtaxtotal += $itemtotallocal * $this->tax/100;
                $itemtotal += $itemtotallocal+$itemtaxtotal;
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotallocal;
            }
        }
        foreach($this->material_items as $key => $item)
        {
            $itemtaxtotal = 0;
            $material = \App\Models\Material::find($item[0]['product']);
            if($tax_type == 2)
            {
                $itemtotallocal =  ($this->matrate[$key] * $this->matqty[$key])  * (100 / (100 + $this->tax ?? 15));
                $itemtaxtotal +=  ($this->matrate[$key] * $this->matqty[$key]) - $itemtotallocal ?? 0;
                $itemtotal +=  ($this->matrate[$key] * $this->matqty[$key]);
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotal;
                $sub_total += $itemtotallocal;
            }
            else{
                $itemtotallocal =  ($this->matqty[$key] * $this->matrate[$key]);
                $itemtaxtotal += $itemtotallocal * $this->tax/100;
                $itemtotal += $itemtotallocal+$itemtaxtotal;
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotallocal;
            }
        }
        if($this->discount_type == 1)
        {
            if($tax_type == 2)
            {
                $this->sub_total = $this->taxable;
                $this->taxable = $itemtotal -  $this->discount;
                $unitprice = $this->taxable  * (100 / (100 + $this->tax ?? 15));
                $this->taxable  = $unitprice;
                $this->taxamount =  ($itemtotal - $this->discount) - $unitprice;
                $this->total = $this->taxable + $this->taxamount;
                $this->sub_total = $sub_total;
                return 1;
            }
            else{
                $this->sub_total = $this->taxable;
                $this->taxable = $itemtotal -  $this->discount;
                $unittax = $this->taxable * $this->tax/100;
                $this->taxamount = $unittax;
                $this->total = $this->taxable ;
                return 1;
            }
        }
        else{
            if($tax_type == 1)
            {
                $this->sub_total = $this->taxable;
                $this->taxable = $itemtotal;
                $unittax = $this->taxable * $this->tax/100;
                $this->taxamount = $unittax;
                $this->total = ($this->taxable + $unittax) - $this->discount;
                $this->sub_total = $sub_total;
                return 1;
            }
            if($tax_type == 2)
            {
                $this->sub_total = $this->taxable;
                $this->taxable = $itemtotal;
                $unitprice = $this->taxable  * (100 / (100 + $this->tax ?? 15));
                $this->taxable  = $unitprice;
                $this->taxamount =  ($itemtotal) - $unitprice;
    
                $this->total = $this->taxable + $this->taxamount;
                $this->total -= $this->discount;
                $this->sub_total = $sub_total;
                return 1;
            }
        }
        $this->taxamount = $itemtaxtotal2;
        $this->total = $this->taxable + $this->taxamount;
        if($this->discount_type == 2)
        {
            $this->total = $this->total - $this->discount;
        }
    }
    /* process while update element */
    public function updated($name,$value)
    { 
        /* if updated value is empty set the value as null */
        if ( $value == '' ) data_set($this, $name, null);
        $this->resetErrorBag();
        if($name == 'discount' || strpos($name,'selling_price') !== false || strpos($name,'quantity') !== false)
        {
            $this->calculateTotal();
        }
    }

    // Save the invoice with specified type.
    public function save($type)
    {
        if($this->stop == true)
        {
            return 1;
        }
        $this->calculateTotal();
        $this->resetErrorBag();
        if($this->paid_amount != '' && $this->pay_mode == '')
        {
            $this->addError('pay_mode','Payment Mode Must Be Selected!');
            return 0;
        }
        if($this->paid_amount > $this->total)
        {
            $this->addError('paid_amount','Paid Amount cannot be greater than total!');
            return 0;
        }
        $invoice = Invoice::create([
            'date' => Carbon::now(),
            'invoice_number'    => generateInvoiceNumber(),
            'customer_name' => $this->selected_customer->first_name,
            'customer_phone'    => $this->selected_customer->phone_number_1,
            'customer_address'  => $this->selected_customer->address,
            'customer_file_number'  => $this->selected_customer->file_number,
            'customer_id'   => $this->selected_customer->id,
            'salesman_id'   => $this->selected_salesman,
            'tax_type'  => getTaxType(),
            'discount'  => $this->discount ?? 0,
            'sub_total' => $this->sub_total,
            'tax_percentage'    => $this->tax,
            'tax_amount'    => $this->taxamount,
            'taxable_amount'    => $this->taxable,
            'total' => $this->total,
            'notes' => $this->notes,
            'created_by'    => Auth::user()->id,
            'financial_year_id' => getFinancialYearID(),
            'branch_id' => Auth::user()->id,
            'status' => 0,
        ]);
        $qtycount =0;
        foreach($this->cart_items as $key => $item)
        {
            $itemtaxtotal = 0;
            $product = \App\Models\Product::find($item[0]['product']);
            $itemtotal = 0;
            $qtycount ++;
            $itemtotallocal=0;
            $itemrate = 0;
            if(getTaxType() == 2)
            {
                $itemrate =  ($this->selling_price[$key])  * (100 / (100 + $this->tax ?? 15));
                $itemtotallocal =  ($this->selling_price[$key] * $this->quantity[$key])  * (100 / (100 + $this->tax ?? 15));
                $itemtaxtotal +=  ($this->selling_price[$key] * $this->quantity[$key]) - $itemtotallocal ?? 0;
                $itemtotal += ($this->selling_price[$key] * $this->quantity[$key]);
            }
            else{
                $itemtotallocal =  ($this->selling_price[$key] * $this->quantity[$key]);
                $itemrate = $this->selling_price[$key];
                $itemtaxtotal += $itemtotallocal * $this->tax/100;
                $itemtotal += $itemtotallocal+$itemtaxtotal;
            }
            InvoiceDetail::create([
                'invoice_id'    => $invoice->id,
                'type'  => 1,
                'tax_amount'    => $itemtaxtotal,
                'quantity'  => $this->quantity[$key],
                'item_id' => $product->id,
                'item_name'=>$product->name,
                'rate' => $itemrate,
                'total' => $itemtotal,
            ]);
        }
        foreach($this->material_items as $key => $item)
        {
            $itemtotal = 0;
            $qtycount ++;
            $itemtaxtotal = 0;
            $material = \App\Models\Material::find($item[0]['product']);
            $itemrate = 0;
            if(getTaxType() == 2)
            {

                $itemrate =  ($this->matrate[$key])  * (100 / (100 + $this->tax ?? 15));
                $itemtotallocal =  ($this->matrate[$key] * $this->matqty[$key])  * (100 / (100 + $this->tax ?? 15));
                $itemtaxtotal +=  ($this->matrate[$key] * $this->matqty[$key]) - $itemtotallocal ?? 0;
                $itemtotal +=  ($this->matrate[$key] * $this->matqty[$key]);
            }
            else{
                $itemtotallocal =  ($this->matqty[$key] * $this->matrate[$key]);
                $itemrate =  $this->matrate[$key];
                $itemtaxtotal += $itemtotallocal * $this->tax/100;
                $itemtotal += $itemtotallocal+$itemtaxtotal;
            }
            InvoiceDetail::create([
                'invoice_id'    => $invoice->id,
                'type'  => 2,
                'tax_amount'    => $itemtaxtotal,
                'quantity'  => $this->matqty[$key],
                'item_id' => $material->id,
                'item_name'=>$material->name,
                'rate' => $itemrate,
                'total' => $itemtotal,
                'unit_type' => $material->unit,
            ]);
        }
        if($this->paid_amount )
        {
            InvoicePayment::create([
                'date' => Carbon::now(),
                'invoice_id'    => $invoice->id,
                'customer_name' => $this->selected_customer->first_name,
                'customer_id'   => $this->selected_customer->id,
                'created_by'    => Auth::user()->id,
                'financial_year_id' => getFinancialYearID(),
                'branch_id' => Auth::user()->id,
                'payment_mode'  => $this->pay_mode,
                'paid_amount'   => $this->paid_amount,
                'note'  => $this->reference,
                
            ]);
        }
        $invoice->total_quantity = $qtycount;
        $invoice->save();
        $this->stop = true;
        if($type==1) {
            $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Invoice Created Successfully!']);
        
            $this->emit('reloadpage');
        } 
        if($type==2)
        {
            $this->inv_id=$invoice->id;
            $this->emit('printWindow');
        }
        $this->sendOrderCreationNotification();
    }

    // Create a new customer.
    public function customerCreate()
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
        $countryCode = getCountryCode(); 

        // Concatenate the country code with the provided phone number
        $phoneNumber = $countryCode . $this->phone_number_1;
        $user = Auth::user();
        $customer = Customer::create([
            'date' =>  Carbon::today(),
            'file_number' => $this->file_number,
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'family_name' => $this->family_name,
            'phone_number_1' => $phoneNumber,
            'phone_number_2' => $this->phone_number_2,
            'is_active' => $this->is_active??0,
            'address' => $this->address,
            'notes' => $this->notes_c,
            'email' => $this->email,
            'created_by' => Auth::user()->id,
            'opening_balance'=>($this->opening_balance!="")?$this->opening_balance:0,
            'customer_group_id'=>($this->customer_group_id!="")?$this->customer_group_id:NULL,
        ]);
        $this->dispatchBrowserEvent('alert',['type' => 'success','title' => 'Success','message' => 'Customer Created Successfully!']);
        $this->selected_customer = $customer;
        $this->customer_query = '';
        $this->customer_results = null;
        $this->emit('closemodal');
    }

    // Automatically fill the paid amount with the total.
    public function magicFill()
    {
        $this->calculateTotal();
        $this->paid_amount = $this->total;
    }

    // Continue the invoicing process with validation.
    public function continue()
    {
        $this->calculateTotal();
       
        if(!$this->selected_customer)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Customer is not selected!']);
            return 1;   
        }
        
        if(count($this->cart_items) == 0 && count($this->material_items)  == 0)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'No items are selected!']);
            return 1; 
        }
        if($this->total < 0)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Total Cannot be less than 0!']);
            return 1;   
        }
        $this->emit('openmodal');
        return true;
    }

    // Change the quantity for a material item.
    public function changeqty($key)
    {
        $this->editkey = null;
        $this->editkey = $key;
        $this->material_qty = $this->matqty[$key];
    }

    // Save changes to a material item's quantity.
    public function saveChanges()
    {
        $this->validate([
            'material_qty'  => 'required|numeric'
        ]);

        $this->matqty[ $this->editkey] = $this->material_qty;
        $this->emit('closemodal');
        $this->calculateTotal();
    }

    /**
     *  purpose   : program to implement Whatsapp Integration 
     * @author   : harikrishna
     * @method : function to  create sendOrderCreationNotification
     *  @since    : 16-11-2023
     */
    public function sendOrderCreationNotification()
    {
    
        $url = 'https://graph.facebook.com/v17.0/178746198644829/messages';
        $accessToken = 'EAAjZBhaxod58BO7k0Q6k3ZC90gBWDKmxpplUnvLu84Sx1lB52Y7LIA9I1A4rTKBnsa9IUUh0hOZCrESQedPjnLgU8hZCoRvBv9IWI2vdIZCmZCNqfJZAKEQHtX04jBUOAAkyMvmY9Y9Jl5cdQ2EjNTylXsaLDZCHauBfAXEyGVYUJTrqjAG9TmSaxXZA8r6m3ubmn';
        //$phoneNumber = "+919501945864"; // Replace with the customer's phone number
        $client = new Client();

     
        $order = Invoice::orderBy('created_at', 'desc')->first();
        // Check if the order exists
        if (!$order) {
            session()->flash('error', 'Order not found.'); 
            return;
            
        }
        // Accessing invoice payments based on the invoice ID
        $invoicePayments = InvoicePayment::where('invoice_id', $order->id)->get();

        

        // Retrieve the associated customer
       $customer = $order->customer; // Assuming the relationship is defined

        if (!$customer) {
            session()->flash('error', 'Customer details not found for this invoice.');
            return;
        }

        //Access order details
        
        $phoneNumber = $customer->phone_number_1;
        $orderNumber = $order->invoice_number;
        $customerName = $order->customer_name;
        $subTotal = $order->sub_total; 
        $taxAmount = $order->tax_amount;
        $total = $order->total;
        $totalPaidAmount = $invoicePayments->sum('paid_amount');
        $Balance = $total -  $totalPaidAmount;

        // Your dynamic URL
        //$dynamicUrl = "https://tailorpos.nshinetechnologies.com/";

        // Build the message
        $messageText = "🎉 Dear $customerName! \n".
                        " We're thrilled to confirm your order ($orderNumber)! 🎁\n\n" .
                        "🛍 Order Summary:\n" .
                        "   - Subtotal: $subTotal\n" .
                        "   - Tax Amount: $taxAmount\n" .
                        "   - Total: $total\n\n" .
                        "   - Paid: $totalPaidAmount\n" .
                        "   - Remaining Balance: $Balance\n" .
                        "🚀 Your order is being prepared meticulously for a delightful experience.\n" .
                        " 📞 Feel free to reach us at +919996665515 if you need any assistance.\n".
                        //"🌐 Visit us: $dynamicUrl\n\n" .
                        "🙏 Thank you for choosing us! We're eagerly preparing your order and looking forward to serving you again soon. 😊";



        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ];

        $message = [
            "messaging_product" => "whatsapp",
            "to" => $phoneNumber,
            "type" => "text",
            "text" => [
                "body" => $messageText
            ]

        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $message,
            ]);
            
            // Handle success response
            session()->flash('success', 'Order creation notification sent successfully!');

            //Dispatch a browser event on success
            $this->dispatchBrowserEvent('order-creation-notification-sent', [
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Order creation notification sent successfully!'
            ]);
            
        
            } catch (RequestException $e) {
            // Handle error response
        
            session()->flash('error', 'Failed to send order creation notification.');
           } 
    }
}
