<?php

namespace App\Http\Livewire\Admin\Invoice;

use App\Models\Invoice;
use App\Models\Translation;
use Livewire\Component;
use Auth;

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
                break;
            case 'pending':
                $order->status = 1;
                $order->save();
                break;
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