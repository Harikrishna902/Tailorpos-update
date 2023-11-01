<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\MasterSetting;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Image;
class CompanySettings extends Component
{
    public $name,$email,$password,$company_name,$name_arabic,$logo,$company_email,$company_mobile,$company_landline,$tax;
    public $cr,$building,$street,$district,$city,$country,$postal,$additional;
    use WithFileUploads;

    //render the page
    public function render()
    {
        return view('livewire.admin.settings.company-settings');
    }

    //load all settings to input fields
    public function mount()
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->company_name = $site['company_name'] ?? '';
        $this->name_arabic = $site['company_name_arabic']?? '';
        $this->company_email = $site['company_email']?? '';
        $this->company_mobile = $site['company_mobile']?? '';
        $this->company_landline = $site['company_landline']?? '';
        $this->tax = $site['company_tax_registration']?? '';
        $this->cr = $site['company_cr_number']?? '';
        $this->building = $site['company_building_number']?? '';
        $this->street = $site['company_street_name']?? '';
        $this->district = $site['company_district']?? '';
        $this->city = $site['company_city_name']?? '';
        $this->country = $site['company_country']?? '';
        $this->postal = $site['company_postal_code']?? '';
        $this->additional = $site['company_additional_number']?? '';
    }

    //save all input
    public function save()
    {
        $this->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'company_name'  => 'required',
            'building'  => 'required',
            'street'  => 'required',
            'district'  => 'required',
            'city'  => 'required',
            'country'  => 'required',
            'postal'  => 'required',
            'company_email' => 'nullable|email',
            'tax'   => 'required|numeric'
        ]);
        $user = User::find(Auth::user()->id);
        $user->name = $this->name;
        $user->email = $this->email;
        if($this->password)
        {
            $user->password = Hash::make($this->password);
        }
        $user->save();
        $settings = new MasterSetting();
        $site = $settings->siteData();
        if($this->logo){
            try{
                $path = $site['company_logo'];
                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
            }
            catch(\Exception $e)
            {}
            $default_logo = $this->logo;
            $input['file'] = time().'.'.$default_logo->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/company-logo');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $imgFile = Image::make($this->logo->getRealPath());
            $imgFile->resize(150, 150)->save($destinationPath.'/'.$input['file']);
            $site['company_logo'] = '/uploads/company-logo/'.$input['file'];
        }
        $site['company_name'] = $this->company_name;
        $site['company_name_arabic'] = $this->name_arabic;
        $site['company_email'] = $this->company_email;
        $site['company_mobile']=$this->company_mobile; 
        $site['company_landline']=$this->company_landline;
        $site['company_tax_registration']=$this->tax;
        $site['company_cr_number']=$this->cr;
        $site['company_building_number']=$this->building;
        $site['company_street_name']=$this->street;
        $site['company_district']=$this->district;
        $site['company_city_name']=$this->city;
        $site['company_country']=$this->country;
        $site['company_postal_code']=$this->postal;
        $site['company_additional_number']=$this->additional;
        foreach ($site as $key => $value) {
            MasterSetting::updateOrCreate(['master_title' => $key],['master_value' => $value]);
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Company Settings has been updated!']);
    }
}