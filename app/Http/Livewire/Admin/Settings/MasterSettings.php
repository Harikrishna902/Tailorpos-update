<?php

namespace App\Http\Livewire\Admin\Settings;


use App\Models\MasterSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Image;
use Livewire\WithFileUploads;

class MasterSettings extends Component
{
    use WithFileUploads;
    public $default_currency_code,$default_application_name,$default_phone_number,$default_financial_year,$default_tax_percentage,$default_country,$default_country_code;
    public $user,$default_logo,$default_favicon;
    public $default_printer=1,$lang,$contact_email,$contact_phone,$app_logo,$favicon,$message;
    public $default_currency_align=1,$default_discount_type=1,$default_tax_name;
    public $valid_till,$default_tax_mode=1,$purchase_add_list,$purchase_payments,$purchase_return,$suppliers,$supplier_ledger;
    public $stock_transfer,$email_configuration,$sms_configuration,$banking,$stock_adjustment,$branches,$staffs,$qr_enabled;
    public $allow_branches_to_create_products = 1, $allow_branches_to_create_materials = 1,$frontend_enabled =0;

    //render the page
    public function render()
    {
        return view('livewire.admin.settings.master-settings');
    }

    //load all settings
    public function mount()
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->default_currency_code = (isset($site['default_currency_code']) && !empty($site['default_currency_code'])) ? $site['default_currency_code'] : '';
        $this->default_application_name = (isset($site['default_application_name']) && !empty($site['default_application_name'])) ? $site['default_application_name'] : '';
        $this->default_phone_number = (isset($site['default_phone_number']) && !empty($site['default_phone_number'])) ? $site['default_phone_number'] : '';
        $this->default_printer = (isset($site['default_printer']) && !empty($site['default_printer'])) ? $site['default_printer'] : '';
        $this->default_country = (isset($site['default_country']) && !empty($site['default_country'])) ? $site['default_country'] : '';
        $this->default_country_code = (isset($site['default_country_code']) && !empty($site['default_country_code'])) ? $site['default_country_code'] : '';
        $this->default_currency_code = (isset($site['default_currency_code']) && !empty($site['default_currency_code'])) ? $site['default_currency_code'] : '';
        $this->default_financial_year = (isset($site['default_financial_year']) && !empty($site['default_financial_year'])) ? $site['default_financial_year'] : "";
        $this->default_currency_align = (isset($site['default_currency_align']) && !empty($site['default_currency_align'])) ? $site['default_currency_align'] : 1;
        $this->default_printer = (isset($site['default_printer']) && !empty($site['default_printer'])) ? $site['default_printer'] : '';
        $this->default_discount_type = (isset($site['default_discount_type']) && !empty($site['default_discount_type'])) ? $site['default_discount_type'] : 1;
        $this->default_tax_name = (isset($site['default_tax_name']) && !empty($site['default_tax_name'])) ? $site['default_tax_name'] : '';
        $this->default_tax_percentage = (isset($site['default_tax_percentage']) && !empty($site['default_tax_percentage'])) ? $site['default_tax_percentage'] : '';
        $this->valid_till = (isset($site['valid_till']) && !empty($site['valid_till'])) ? $site['valid_till'] : "";
        $this->default_tax_mode = (isset($site['default_tax_mode']) && !empty($site['default_tax_mode'])) ? $site['default_tax_mode'] : 1;
        $this->purchase_add_list = (isset($site['purchase_add_list']) && !empty($site['purchase_add_list'])) ? $site['purchase_add_list'] : 0;
        $this->purchase_payments = (isset($site['purchase_payments']) && !empty($site['purchase_payments'])) ? $site['purchase_payments'] : 0;
        $this->purchase_return = (isset($site['purchase_return']) && !empty($site['purchase_return'])) ? $site['purchase_return'] : 0;
        $this->suppliers = (isset($site['suppliers']) && !empty($site['suppliers'])) ? $site['suppliers'] : 0;
        $this->supplier_ledger = (isset($site['supplier_ledger']) && !empty($site['supplier_ledger'])) ? $site['supplier_ledger'] : 0;
        $this->stock_transfer = (isset($site['stock_transfer']) && !empty($site['stock_transfer'])) ? $site['stock_transfer'] : 0;
        $this->email_configuration = (isset($site['email_configuration']) && !empty($site['email_configuration'])) ? $site['email_configuration'] : 0;
        $this->sms_configuration = (isset($site['sms_configuration']) && !empty($site['sms_configuration'])) ? $site['sms_configuration'] : 0;
        $this->banking = (isset($site['banking']) && !empty($site['banking'])) ? $site['banking'] : 0;
        $this->stock_adjustment = (isset($site['stock_adjustment']) && !empty($site['stock_adjustment'])) ? $site['stock_adjustment'] : 0;
        $this->branches = (isset($site['branches']) && !empty($site['branches'])) ? $site['branches'] : 0;
        $this->staffs = (isset($site['staffs']) && !empty($site['staffs'])) ? $site['staffs'] : 0;
        $this->sms_configuration = (isset($site['sms_configuration']) && !empty($site['sms_configuration'])) ? $site['sms_configuration'] : 0;
        $this->qr_enabled = (isset($site['qr_enabled']) && !empty($site['qr_enabled'])) ? $site['qr_enabled'] : 0;
        $this->frontend_enabled = (isset($site['frontend_enabled']) ) ? $site['frontend_enabled'] : 1;
        $this->allow_branches_to_create_products = (isset($site['allow_branches_to_create_products']) && !empty($site['allow_branches_to_create_products'])) ? $site['allow_branches_to_create_products'] : 1;
        $this->allow_branches_to_create_materials = (isset($site['allow_branches_to_create_materials']) && !empty($site['allow_branches_to_create_materials'])) ? $site['allow_branches_to_create_materials'] : 1;
    }

    //save master settings
    public function save()
    {
        $this->validate([
            'default_currency_code' => 'required',
            'default_country_code' => 'required',
            'default_application_name' => 'required',
            'default_financial_year' =>'required',
            'default_tax_name' => 'required',
            'default_tax_percentage'=> 'required'
        ]);
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $site['default_application_name'] = $this->default_application_name;
        $site['default_currency_code']  = $this->default_currency_code;
        $site['default_country_code']  = $this->default_country_code;
        $site['supplier_ledger']  = $this->supplier_ledger;
        $site['default_printer'] = $this->default_printer;
        $site['default_financial_year'] = $this->default_financial_year;
        $site['default_currency_align'] = $this->default_currency_align;
        $site['default_discount_type'] = $this->default_discount_type;
        $site['default_tax_name'] = $this->default_tax_name;
        $site['purchase_add_list'] = $this->purchase_add_list;
        $site['default_tax_percentage'] = $this->default_tax_percentage;
        $site['valid_till'] = $this->valid_till;
        $site['default_tax_mode'] = $this->default_tax_mode;
        $site['purchase_payments'] = $this->purchase_payments;
        $site['purchase_add_list'] = $this->purchase_add_list;
        $site['purchase_return'] = $this->purchase_return;
        $site['suppliers'] = $this->suppliers;
        $site['stock_transfer'] = $this->stock_transfer;
        $site['email_configuration'] = $this->email_configuration;
        $site['sms_configuration'] = $this->sms_configuration;
        $site['banking'] = $this->banking;
        $site['stock_adjustment'] = $this->stock_adjustment;
        $site['branches'] = $this->branches;
        $site['staffs'] = $this->staffs;
        $site['supplier_ledger'] = $this->supplier_ledger;
        $site['qr_enabled'] = $this->qr_enabled;
        $site['allow_branches_to_create_products'] = $this->allow_branches_to_create_products;
        $site['allow_branches_to_create_products'] = $this->allow_branches_to_create_products;
        $site['allow_branches_to_create_materials'] = $this->allow_branches_to_create_materials;
        $site['frontend_enabled'] = $this->frontend_enabled ;
        if($this->app_logo){
            try{
                $path = $site['default_logo'];
                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
            }
            catch(\Exception $e)
            {}
            $default_logo = $this->app_logo;
            $input['file'] = time().'.'.$default_logo->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/logo');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $imgFile = Image::make($this->app_logo->getRealPath());
            $imgFile->resize(150, 30)->save($destinationPath.'/'.$input['file']);
            $site['default_logo'] = '/uploads/logo/'.$input['file'];
        }
        /* if default_favicon is exists */
        if($this->favicon){
            try{
                $path = $site['default_favicon'];
                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
            }
            catch(\Exception $e)
            {}
            $default_favicon = $this->favicon;
            $input['file'] = time().'.'.$default_favicon->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/favicon');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $imgFile = Image::make($this->favicon->getRealPath());
            $imgFile->resize(26, 26)->save($destinationPath.'/'.$input['file']);
            $site['default_favicon'] = '/uploads/favicon/'.$input['file'];
        }
        foreach ($site as $key => $value) {
            MasterSetting::updateOrCreate(['master_title' => $key],['master_value' => $value]);
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Master Settings Updated Successfully!']);
    }
}