<?php

namespace App\Http\Livewire\Admin\Stock;

use Livewire\Component;
use App\Models\Translation;
use App\Models\StockTransfer;
use App\Models\User;
use Auth;
use App\Models\MasterSetting;

class StockTransferView extends Component
{
    public $current_id,$transfer,$firm_name,$firm_logo;

    //render the page
    public function render()
    {
        return view('livewire.admin.stock.stock-transfer-view');
    }

    //load stock transfer 
    public function mount($id){
        $this->transfer = StockTransfer::where('created_by',Auth::user()->id)->where('id',$id)->first();
        if(!($this->transfer)){
            abort(404);
        }
        $this->current_id = $id;
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->firm_name = $site['company_name'] ?? 'Tailor POS';
        $this->firm_logo = asset($site['company_logo'] ?? '/assets/images/sample.jpg') ?? '/assets/images/sample.jpg';
    }
}