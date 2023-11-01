<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\MasterSetting;
use Livewire\Component;

class TermsAndConditions extends Component
{
    public $content;
    public function render()
    {
        return view('livewire.frontend.pages.terms-and-conditions')->layout('layouts.frontend');
    }

    public function mount()
    {
        $masterSettings = new MasterSetting();
        $siteData = $masterSettings->siteData();
        $this->content = isset($siteData['terms_conditions']) ? $siteData['terms_conditions'] : '';
    }
}
