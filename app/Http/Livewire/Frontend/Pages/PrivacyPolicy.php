<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\MasterSetting;
use Livewire\Component;

class PrivacyPolicy extends Component
{
    public $content;
    public function render()
    {
        return view('livewire.frontend.pages.privacy-policy')->layout('layouts.frontend');
    }

    public function mount()
    {
        $masterSettings = new MasterSetting();
        $siteData = $masterSettings->siteData();
        $this->content = isset($siteData['privacy_policy']) ? $siteData['privacy_policy'] : '';
    }
}
