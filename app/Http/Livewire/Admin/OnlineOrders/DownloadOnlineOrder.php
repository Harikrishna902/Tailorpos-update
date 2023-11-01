<?php

namespace App\Http\Livewire\Admin\OnlineOrders;

use App\Models\MasterSetting;
use App\Models\OnlineOrder;
use Livewire\Component;

class DownloadOnlineOrder extends Component
{
    public function render()
    {
        return view('livewire.admin.online-orders.download-online-order');
    }

     //load default settings
     public function mount() {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->company_name = $site['company_name'] ?? 'Tailor POS';
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->invoice = OnlineOrder::where('id',$id)->first();
        }
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->invoice = OnlineOrder::where('id',$id)->where('branch_id',Auth::user()->id)->first();
        }
        if(!$this->invoice){
            abort(404);
        }
    }
}
