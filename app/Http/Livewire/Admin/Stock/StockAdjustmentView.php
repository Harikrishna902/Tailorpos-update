<?php

namespace App\Http\Livewire\Admin\Stock;

use App\Models\MasterSetting;
use App\Models\StockAdjustment;
use App\Models\Translation;
use Livewire\Component;

class StockAdjustmentView extends Component
{
    public $stockadjust,$firm_logo,$firm_name,$tax,$inv_id;
    
    //render the page
    public function render()
    {
        return view('livewire.admin.stock.stock-adjustment-view');
    }

    //find stock adjustment and basic info
    public function mount($id)
    {
        $this->stockadjust = StockAdjustment::findOrFail($id);
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->firm_name = $site['company_name'] ?? 'Tailor POS';
        $this->tax = $site['company_tax_registration'] ?? '';
        $this->firm_logo = asset($site['company_logo'] ?? '/assets/images/sample.jpg') ?? '/assets/images/sample.jpg';
    }
}
