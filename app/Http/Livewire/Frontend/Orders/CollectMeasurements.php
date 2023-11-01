<?php

namespace App\Http\Livewire\Frontend\Orders;

use App\Models\OnlineOrder;
use App\Models\OnlineOrderDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CollectMeasurements extends Component
{
    public $itemList = [],$tax_total=0,$total=0,$subtotal=0,$branch,$fullData;
    public function render()
    {
        return view('livewire.frontend.orders.collect-measurements')->layout('layouts.frontend');
    }

    public function mount()
    {
        $itemList = Session::get('final_cart_array');
        if(!$itemList)
        {
            return redirect()->route('frontend');
        }
        try{
            $this->fullData = $itemList;
            $this->itemList = $itemList['items'];
            $this->tax_total = $itemList['tax_total'];
            $this->subtotal = $itemList['subtotal'];
            $this->total = $itemList['total'];
            $session_branch = Session::get('current_branch');
            if($session_branch)
            {
                $this->branch = User::whereIn('user_type',[2,3])->whereId($session_branch)->first();
                if(!$this->branch)
                {
                    $this->branch = User::whereUserType(2)->first();
                }
            }
            else{
                $this->branch = User::whereUserType(2)->first();
                Session::put('current_branch',$this->branch->id);
            }
        }
       catch(\Exception $e)
       {
            return redirect()->route('frontend');
       }
    }

    public function save()
    {
        $order = new OnlineOrder();
        $order->order_number = generateOrderNumber();
        $order->date = Carbon::now();
        $order->branch_id = $this->branch->id;
        $order->created_by = Auth::guard('customer')->user()->id;
        $order->customer_id = Auth::guard('customer')->user()->id;
        $order->customer_name = Auth::guard('customer')->user()->name;
        $order->address = $this->fullData['data']['address'];
        $order->country = $this->fullData['data']['country'];
        $order->state = $this->fullData['data']['state'];
        $order->city = $this->fullData['data']['city'];
        $order->zip_code = $this->fullData['data']['zip_code'];
        $order->preferred_delivery_time = $this->fullData['data']['preferred_time'];
        $order->notes = $this->fullData['data']['notes'];
        $order->tax_type = getTaxType();
        $order->status = 0;
        $order->sub_total = $this->subtotal;
        $order->tax_percentage = getTaxPercentage();
        $order->tax_amount = $this->tax_total;
        $order->taxable_amount = $this->subtotal;
        $order->total = $this->total;
        $order->financial_year_id = getFinancialYearID();
        $order->save();

        foreach($this->itemList as $index => $item)
        {
            $orderDetail = new OnlineOrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->tax_amount = $item['taxtotal'];
            $orderDetail->quantity = $item['quantity'];
            $orderDetail->item_id = $item['id'];
            $orderDetail->item_name = $item['name'];
            $orderDetail->rate = $item['rate'];
            $orderDetail->total = $item['total'];
            $orderDetail->notes = isset($item['notes']) ? $item['notes'] : null;
            $orderDetail->save();
        }
        Session::remove('final_cart_array');
        Session::remove('cart_array_items');
        return $order->order_number;
    }
}