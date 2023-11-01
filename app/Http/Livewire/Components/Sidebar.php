<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\MasterSetting;
use App\Models\Translation;

class Sidebar extends Component
{
    public $company_name;
    //render the page
    public function render()
    {
        return view('livewire.components.sidebar');
    }

    //load master settings
    public function mount()
    {
        $settings  = new MasterSetting();
        $site = $settings->siteData();
        $this->company_name = $site['company_name'] ?? 'Tailor POS'; 
    }

    //check if financial year id is set
    public function check()
    {
        if(getFinancialYearID() == null)
        {
            $this->dispatchBrowserEvent(
                'swal-alert', ['type' => 'error','title'=> 'Financial Year Not Set!',  'message' => 'Contact an admin!']);
            return 1;
        }
        return redirect()->route('admin.invoice');
    }
}