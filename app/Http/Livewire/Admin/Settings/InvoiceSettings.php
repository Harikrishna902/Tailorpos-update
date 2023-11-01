<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;

use App\Models\MasterSetting;

class InvoiceSettings extends Component
{
    public $default_invoice_index,$default_invoice_prefix;
    
    //render the page
    public function render()
    {
        return view('livewire.admin.settings.invoice-settings');
    }

    //load default index and prefix
    public function mount()
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->default_invoice_index = (isset($site['default_invoice_index']) && !empty($site['default_invoice_index'])) ? $site['default_invoice_index'] : 1;
        $this->default_invoice_prefix = (isset($site['default_invoice_prefix']) && !empty($site['default_invoice_prefix'])) ? $site['default_invoice_prefix'] : '';
    }

    //save to master settings
    public function save()
    {
        $this->validate([
            'default_invoice_index' => 'required',
            'default_invoice_prefix' => 'required|ends_with:-',
        ]);
        $count = substr_count($this->default_invoice_prefix,'-');
        if($count > 1)
        {
            $this->addError('default_invoice_prefix','Cannot repeat - more than once');
            return;
        }
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $site['default_invoice_index'] = $this->default_invoice_index;
        $site['default_invoice_prefix']  = $this->default_invoice_prefix;
                foreach ($site as $key => $value) {
            MasterSetting::updateOrCreate(['master_title' => $key],['master_value' => $value]);
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Invoice Settings Updated Successfully!']);
    }
}