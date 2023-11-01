<?php

namespace App\Http\Livewire\Admin\Pages;

use App\Models\MasterSetting;
use Livewire\Component;

class PrivacyPolicyPage extends Component
{
    public $content;
    public function render()
    {
        return view('livewire.admin.pages.privacy-policy-page');
    }

    public function mount()
    {
        $masterSettings = new MasterSetting();
        $siteData = $masterSettings->siteData();
        $this->content = isset($siteData['privacy_policy']) ? $siteData['privacy_policy'] : '';
    }

    public function save()
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $site['privacy_policy'] = $this->content;
        foreach ($site as $key => $value) {
            MasterSetting::updateOrCreate(['master_title' => $key],['master_value' => $value]);
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Privacy Policy Updated Successfully!']);
    }
}