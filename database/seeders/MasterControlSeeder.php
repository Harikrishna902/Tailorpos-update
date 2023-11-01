<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterSetting;

class MasterControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $site['default_application_name'] = 'Tailor';
        $site['default_currency_code']  = "USD";
        $site['default_country_code']   = "+1";
        $site['default_printer'] = '1';
        $site['default_tax_name'] ='TAX';
        $site['default_tax_percentage']='15';
        $site['qr_enabled']='0';
        foreach ($site as $key => $value) {
            MasterSetting::updateOrCreate(['master_title' => $key],['master_value' => $value]);
        }
    }
}