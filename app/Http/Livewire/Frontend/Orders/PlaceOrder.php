<?php

namespace App\Http\Livewire\Frontend\Orders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PlaceOrder extends Component
{
    public $address,$country,$state,$city,$zip_code,$preferred_time,$notes,$itemList=[],$subtotal=0,$tax_total=0,$total = 0,$taxable =0 ,$tax=0;
    public function render()
    {
        return view('livewire.frontend.orders.place-order')->layout('layouts.frontend');
    }

    public function mount()
    {
        $itemList = Session::get('cart_array_items');
        if(!$itemList)
        {
            return redirect()->route('frontend');
        }
        $this->itemList = $itemList['items'];
        $this->preferred_time = Carbon::now()->addWeek()->format('Y-m-d h:i:s');
        $this->tax = getTaxPercentage();
        $this->calculateItems();
    }

    public function save()
    {
        $this->validate([
            'address'   => 'required',
            'preferred_time' => 'required'
        ]);

        $cartArray = [];
        $cartArray['address'] = $this->address;
        $cartArray['country'] = $this->country;
        $cartArray['state'] = $this->state;
        $cartArray['city'] = $this->city;
        $cartArray['zip_code'] = $this->zip_code;
        $cartArray['preferred_time'] = $this->preferred_time;
        $cartArray['notes'] = $this->notes;
        $this->calculateItems();
        $finalArray = [
            'data'  => $cartArray,
            'subtotal'  => $this->subtotal,
            'total' => $this->total,
            'tax_total' => $this->tax_total,
            'items' => $this->itemList
        ];
        Session::put('final_cart_array',$finalArray);
        return redirect()->route('frontend.collect-measurements');
    }

    public function getProducts()
    {
        $products = Product::whereIsActive(1)->get();
        return json_encode($products);
    }

    public function calculateItems()
    {
        $this->subtotal =0;
        $this->tax_total = 0;
        $this->taxable = 0;
        $unitprice =0;
        $itemtotal = 0;
        $itemtaxtotal2 = 0;
        $sub_total =0;
        $tax_type = getTaxType();
        foreach($this->itemList as $index => $item)
        {
            $itemtaxtotal = 0;
            $product = \App\Models\Product::find($item['id']);
            if($tax_type == 2)
            {
                $itemtotallocal =  ($item['stitching_cost'] * $item['quantity'])  * (100 / (100 + $this->tax ?? 15));
                $itemtaxtotal +=  ($item['stitching_cost'] * $item['quantity']) - $itemtotallocal ?? 0;
                $itemtotal +=( $item['stitching_cost'] * $item['quantity']);
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotallocal;
                $sub_total += $itemtotallocal;
                $this->itemList[$index]['taxtotal'] = ($item['stitching_cost'] * $item['quantity']) - $itemtotallocal ?? 0;
                $this->itemList[$index]['total'] = ($item['stitching_cost'] * $item['quantity']);
                $this->itemList[$index]['rate'] = $item['stitching_cost'];
            }
            else{
                $itemtotallocal =  ($item['stitching_cost'] * $item['quantity']);
                $itemtaxtotal += $itemtotallocal * $this->tax/100;
                $itemtotal += $itemtotallocal+$itemtaxtotal;
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotallocal;
                $this->itemList[$index]['taxtotal'] = $itemtotallocal * $this->tax/100;
                $this->itemList[$index]['total'] = $itemtotallocal;
                $this->itemList[$index]['rate'] = $item['stitching_cost'];
            }
        }
        $this->tax_total = $itemtaxtotal2;
        $this->subtotal = $this->taxable;
        $this->total = $this->taxable + $this->tax_total;

    }

    public function saveNote($id,$note)
    {
        $outItem = null;
        foreach($this->itemList as $index => $item)
        {
            if($item['id'] == $id)
            {
                $outItem = $index;
            }
        }
        $this->itemList[$outItem]['notes'] = $note;
    }
}