<?php

namespace App\Http\Livewire\Admin\Invoice;

use App\Models\Invoice;
use App\Models\Translation;
use Livewire\Component;
use Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicePayment;

class StatusScreen extends Component
{
    public $orders,$pending_orders,$processing_orders,$ready_orders,$lang,$order;

    // Initialization and setup logic for the component.
    public function mount()
    {
        
    }

    //render the page,get different orders for drag and drop
    public function render()
    {
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->pending_orders = Invoice::where('status',1)->where('created_by',Auth::user()->id)->latest()->get();
            $this->processing_orders = Invoice::where('status',2)->where('created_by',Auth::user()->id)->latest()->get();
            $this->ready_orders = Invoice::where('status',3)->where('created_by',Auth::user()->id)->latest()->get();
        }   
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->pending_orders = Invoice::where('status',1)->where('created_by',Auth::user()->id)->latest()->get();
            $this->processing_orders = Invoice::where('status',2)->where('created_by',Auth::user()->id)->latest()->get();
            $this->ready_orders = Invoice::where('status',3)->where('created_by',Auth::user()->id)->latest()->get();
        }
        return view('livewire.admin.invoice.status-screen');
    }

     /* change the order status */
     public function changestatus($order,$status)
     {
        $order = Invoice::where('id',$order)->first();
        switch($status)
        {
            case 'processing':
                $order->status = 2;
                $order->save();
                break;
            case 'ready':
                $order->status = 3;
                $order->save();
                //to call sendReadyForDeliveryNotification when order status is changed to 3
                $this->sendReadyForDeliveryNotification($order);
                break;
            case 'pending':
                $order->status = 1;
                $order->save();
                break;
        }
    }

    /**
     *  purpose   : program to implement Whatsapp Integration  to send delivery notification
     * @author   : harikrishna
     * @method : function to  create sendReadyForDeliveryNotification
     * @since    : 16-11-2023
     */
    public function sendReadyForDeliveryNotification($order)
    {
    
        $url = 'https://graph.facebook.com/v17.0/178746198644829/messages';
        $accessToken = 'EAAjZBhaxod58BO7k0Q6k3ZC90gBWDKmxpplUnvLu84Sx1lB52Y7LIA9I1A4rTKBnsa9IUUh0hOZCrESQedPjnLgU8hZCoRvBv9IWI2vdIZCmZCNqfJZAKEQHtX04jBUOAAkyMvmY9Y9Jl5cdQ2EjNTylXsaLDZCHauBfAXEyGVYUJTrqjAG9TmSaxXZA8r6m3ubmn';
        //$phoneNumber = "+919501945864"; // Replace with the customer's phone number
        $client = new Client();

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
        $dynamicUrl = "https://tailorpos.nshinetechnologies.com/";

        // Build the message
        $messageText = "🎉 Hello $customerName! \n".
                        " Your order ($orderNumber) is all set for delivery! 🚚💨\n\n" .
                        "🛍 Order Summary:\n" .
                        "   - Subtotal: $subTotal\n" .
                        "   - Tax : $taxAmount\n" .
                        "   - Total Amount: $total\n\n" .
                        "   - Paid: $totalPaidAmount\n" .
                        "   - Balance Due: $Balance\n" .
                        "🚀 Your order has been carefully prepared and is ready to make its way to you! 📦🎁 \n" .
                        " 📞 If you have any queries or need assistance, feel free to reach out at +919996665515.\n".
                       // "🌐 Visit us: $dynamicUrl\n\n" .
                        "🙏 Thank you for choosing us! We're excited to deliver your order and look forward to serving you again soon. 😊";



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
                session()->flash('success', 'Ready for delivery notification sent successfully!');
                //dd('Event emitted');
                $this->emit('delivery-notification-sent');
                
            } catch (RequestException $e) {
                // Handle error response
                session()->flash('error', 'Failed to send ready for delivery notification.');
            }
    }
    
    //view order
    public function viewOrder($id)
    {
        $this->order = Invoice::find($id); 
    }

    //change the order status to delivered
    public function confirmDelivery()
    {
        if($this->order)
        {
            $this->order->status = 4;
            $this->order->save();
            $this->order = null;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Invoice was marked as delivered!']);
        }
    }
}
