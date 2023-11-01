<?php

namespace App\Http\Livewire\Admin\Pages;

use App\Models\MasterSetting;
use Livewire\Component;

class TermsConditionsPage extends Component
{
    public $content;
    public function render()
    {
        return view('livewire.admin.pages.terms-conditions-page');
    }


    public function mount()
    {
        $masterSettings = new MasterSetting();
        $siteData = $masterSettings->siteData();
        $this->content = isset($siteData['terms_conditions']) ? $siteData['terms_conditions'] : '';
    }

    public function save()
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $site['terms_conditions'] = $this->content;
        foreach ($site as $key => $value) {
            MasterSetting::updateOrCreate(['master_title' => $key],['master_value' => $value]);
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Terms & Conditions were Updated Successfully!']);
    }
}
