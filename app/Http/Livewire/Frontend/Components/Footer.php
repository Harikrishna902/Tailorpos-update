<?php

namespace App\Http\Livewire\Frontend\Components;

use App\Models\MasterSetting;
use Livewire\Component;

class Footer extends Component
{
    public $phone,$email;
    public function render()
    {
        return view('livewire.frontend.components.footer');
    }

    public function mount()
    {
        $masterSettings = new MasterSetting();
        $siteData = $masterSettings->siteData();
        $this->phone = $siteData['company_mobile'] ||  !$siteData['company_mobile'] == '' ?  $siteData['company_mobile'] : '00000000'; 
        $this->email = $siteData['company_email'] ||  !$siteData['company_email'] == '' ?  $siteData['company_email'] : 'email@email.com';
    }
}
