<?php

namespace App\Http\Livewire\Admin\OnlineOrders;

use App\Models\MasterSetting;
use App\Models\OnlineOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintOnlineOrder extends Component
{
    public $invoice, $firm_logo, $firm_name, $tax, $inv_id;
    public function render()
    {
        return view('livewire.admin.online-orders.print-online-order')->extends('layouts.print-layout');
    }

    //load viewing sale  if it can be viewed by logged in user
    public function mount($id)
    {
        if (Auth::user()->user_type == 2) {
            $this->invoice = OnlineOrder::where('id', $id)->first();
        }
        if (Auth::user()->user_type == 3) {
            $this->invoice = OnlineOrder::where('id', $id)->where('branch_id', Auth::user()->id)->first();
        }
        if (!$this->invoice) {
            abort(404);
        }
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->firm_name = $site['company_name'] ?? 'Tailor POS';
        $this->tax = $site['company_tax_registration'] ?? '';
        $this->firm_logo = asset($site['company_logo'] ?? '/assets/images/sample.jpg') ?? '/assets/images/sample.jpg';
    }
}
