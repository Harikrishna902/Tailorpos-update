<?php

use App\Models\Customer;
use App\Models\MasterSetting;
use Illuminate\Support\Facades\Auth;

    if(!function_exists('getPaymentMode'))
    {
        //Get Payment Mode 
        function getPaymentMode($payment_mode)
        {
            switch($payment_mode)
            {
                case 1:
                    return __('main.cash');
                case 2:
                    return __('main.card');
                case 3:
                    return __('main.upi');
                case 4:
                    return __('main.cheque');
                case 5:
                    return __('main.bank_transfer');
                default:
                    return '';
            }
        }
    }

    if(!function_exists('getApplicationName'))
    {
        //Application name 
        function getApplicationName()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_application_name']))
            {
                $currency = (($site['default_application_name']) && ($site['default_application_name'] !=""))? $site['default_application_name'] : 'Tailor POS';
                return $currency;
            }
            return 'Tailor POS';
        }
    }

    if(!function_exists('getCompanyTaxRegistration'))
    {
        //Application name 
        function getCompanyTaxRegistration()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['company_tax_registration']))
            {
                $currency = (($site['company_tax_registration']) && ($site['company_tax_registration'] !=""))? $site['company_tax_registration'] : '';
                return $currency;
            }
            return '';
        }
    }

    if(!function_exists('getFavIcon'))
    {
        //Get fav icon
        function getFavIcon()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_favicon']))
            {
                $currency = (($site['default_favicon']) && ($site['default_favicon'] !=""))? $site['default_favicon'] : '/assets/images/favicon.png';
                return asset($currency);
            }
            return asset('/assets/images/fav.png');
        }
    }
    if(!function_exists('getApplicationLogo'))
    {
        //get application logo
        function getApplicationLogo()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_logo']))
            {
                $currency = (($site['default_logo']) && ($site['default_logo'] !=""))? $site['default_logo'] : '/assets/images/logo/logo.png';
                return asset($currency);
            }
            return asset('/assets/images/logo/logo.png');
        }
    }

    if(!function_exists('getCountryCode'))
    {
        //get country code
        function getCountryCode() {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_country_code']))
            {
                $favicon = (($site['default_country_code']) && ($site['default_country_code'] !=""))? $site['default_country_code'] : '+1';
                return $favicon;
            }
            return '+1';
        }
    }

    if(!function_exists('getCurrency'))
    {
        //get curreny
        function getCurrency() {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_currency_code']))
            {
                $currency = (($site['default_currency_code']) && ($site['default_currency_code'] !=""))? $site['default_currency_code'] : 'USD';
                return $currency;
            }
            return 'USD';
        }
    }

    if(!function_exists('getUserType'))
    {
        //get user type
        function getUserType($type)
        {
           
            switch($type)
            {
                case 2:
                    return __('main.admin');
                case 3:
                    return __('main.branch');
                case 4:
                    return __('main.salesman');
                case 5:
                    return __('main.tailor');
                case 6:
                    return __('accountant');
            }
        }
    }

    if(!function_exists('getTaxPercentage'))
    {
        //get tax percentage
        function getTaxPercentage()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_tax_percentage']))
            {
                $currency = (($site['default_tax_percentage']) && ($site['default_tax_percentage'] !=""))? $site['default_tax_percentage'] : 15;
                return $currency;
            }
            return 15;
        }
    }
    if(!function_exists('getTaxType'))
    {
        //get tax type
        function getTaxType()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_tax_mode']))
            {
                $currency = (($site['default_tax_mode']) && ($site['default_tax_mode'] !=""))? $site['default_tax_mode'] : 1;
                return $currency;
            }
            return 1;
        }
    }

    if(!function_exists('getCompanyLogo'))
    {
        //get company logo
        function getCompanyLogo()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['company_logo']) && file_exists(public_path($site['company_logo'])))
            {
                return asset($site['company_logo']);
            }
            return asset('assets/images/sample.jpg');
        }
    }

    if(!function_exists('getCompanyLogoPdf'))
    {
        //get company logo
        function getCompanyLogoPdf()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['company_logo']) && file_exists(public_path($site['company_logo'])))
            {
                return public_path($site['company_logo']);
            }
            return public_path('assets/images/sample.jpg');
        }
    }
    if(!function_exists('getFormattedCurrency'))
    {
        //get formatted currency
        function getFormattedCurrency($value)
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            $symbol = $site['default_currency_code'] ?? 'USD';
            $alignment = $site['default_currency_align'] ?? 1;
            $value = number_format($value,2);
            if($alignment == 1)
            {
                return $symbol.' '.$value;
            }
            return $value.' '.$symbol;
        }
    }
    if(!function_exists('getUnitType'))
    {
        //get unit type
        function getUnitType($type)
        {
            switch($type)
            {
                case 1:
                    return __('main.mtr');
                case 2:
                    return __('main.yrd');
                case 3:
                    return __('main.cm');
                case 4:
                    return __('main.nos');
                case 5:
                    return __('main.pcs');
                case 6:
                    return __('main.dzn');
                case 7:
                    return __('main.box');
                case 8:
                    return __('main.gm');     
                case 9:
                    return __('main.kg');     
            }
        }
    }
    if(!function_exists('getFinancialYearID'))
    {
        //get financial year id
        function getFinancialYearID()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_financial_year']))
            {
                $year_id = (($site['default_financial_year']) && ($site['default_financial_year'] !=""))? $site['default_financial_year'] : '';
                return $year_id;
            }
            return null;
        }
    }
    if(!function_exists('getDiscountType'))
    {
        //get discount type
        function getDiscountType()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_discount_type']))
            {
                $year_id = (($site['default_discount_type']) && ($site['default_discount_type'] !=""))? $site['default_discount_type'] : 1;
                return $year_id;
            }
            return 1;
        }
    }
    if(!function_exists('generateInvoiceNumber'))
    {
        //generate invoice number
        function generateInvoiceNumber()
        {
            $ordernumber = App\Models\Invoice::Orderby('id', 'desc')->where('financial_year_id',getFinancialYearID())->latest()->first();
            $mastersetting = new MasterSetting();
            $siteData = $mastersetting->siteData();
            $prefix = $siteData['default_invoice_prefix'] ?? 'INV-';
            if($ordernumber)
            {
                if($ordernumber && $ordernumber->invoice_number!=""){
                    /* if order code not empty */
                    $code=$ordernumber->invoice_number;
                    $code=explode("-", $ordernumber->invoice_number);
                    $new_code = $code[1] + 1;
                    return $prefix.$new_code;
                }
                else{
                    return  $prefix.'1';
                }
            }
            elseif(!\App\Models\Invoice::latest()->first()){

                return $prefix.($siteData['default_invoice_index'] ?? 1) ?? 1;
            }
            return $prefix.'1';
        }
    }
    if(!function_exists('generateOrderNumber'))
    {
        //generate invoice number
        function generateOrderNumber()
        {
            $ordernumber = App\Models\OnlineOrder::Orderby('id', 'desc')->where('financial_year_id',getFinancialYearID())->latest()->first();
            $mastersetting = new MasterSetting();
            $siteData = $mastersetting->siteData();
            $prefix = $siteData['default_invoice_prefix'] ?? 'INV-';
            if($ordernumber)
            {
                if($ordernumber && $ordernumber->order_number!=""){
                    /* if order code not empty */
                    $code=$ordernumber->order_number;
                    $code=explode("-", $ordernumber->order_number);
                    $new_code = $code[1] + 1;
                    return $prefix.$new_code;
                }
                else{
                    return  $prefix.'1';
                }
            }
            elseif(!\App\Models\OnlineOrder::latest()->first()){
                return $prefix.($siteData['default_invoice_index'] ?? 1) ?? 1;
            }
            return $prefix.'1';
        }
    }
    if(!function_exists('generatePurchaseReturnNumber'))
    {
        //generate purchase return number
        function generatePurchaseReturnNumber()
        {
            $code_prefix='PR-';
            $returnnumber = App\Models\PurchaseReturn::Orderby('id', 'desc')->where('financial_year_id',getFinancialYearID())->latest()->first();
                        if($returnnumber && $returnnumber->purchase_return_number!=""){
            /* if invoice code not empty */
            $code=explode("-", $returnnumber->purchase_return_number);
            $new_code = $code[1] + 1;
            return $code_prefix.$new_code;
        }else{
            /* if invoice code is empty set start */
            return $code_prefix.'1';
        }
        }
    }
    if(!function_exists('generateSPno'))
    {
        //generate purchase number
        function generateSPno()
        {
            $code_prefix='SP-';
            $returnnumber = App\Models\SalesReturnPayment::Orderby('id', 'desc')->where('financial_year_id',getFinancialYearID())->latest()->first();
                        if($returnnumber && $returnnumber->voucher_no!=""){
            /* if invoice code not empty */
            $code=explode("-", $returnnumber->voucher_no);
            $new_code = $code[1] + 1;
            return $code_prefix.$new_code;
        }else{
            /* if invoice code is empty set start */
            return $code_prefix.'1';
        }
        }
    }
    if(!function_exists('getInvoiceStatus'))
    {
        //get invoice status
        function getInvoiceStatus($status)
        {
           
            switch($status)
            {
                case 1:
                    return __('main.pending');
                case 2:
                    return __('main.processing');
                case 3:
                    return __('main.ready_to_deliver');
                case 4:
                    return __('main.delivered');
            }
            
        }
    }
    if(!function_exists('getOrderStatus'))
    {
        //get order status
        function getOrderStatus($status)
        {
           
            switch($status)
            {
                case 0:
                    return __('main.pending');
                case 1:
                    return __('main.processing');
                case 2:
                    return __('main.ready_to_deliver');
                case 3:
                    return __('main.delivered');
            }
            
        }
    }
    if(!function_exists('isRTL'))
    {
        //returns if RTL is set or not
        function isRTL() {
            if(session()->has('selected_language'))
            {  
                $lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
                if($lang)
                {
                    if($lang->is_rtl)
                    {
                        return true;
                    }
                }
            }
            return false;
        }
    }
    
    if(!function_exists('getInvoiceStatusColor'))
    {
        //get invoice status colors
        function getInvoiceStatusColor($status)
        {
            switch($status)
            {
                case 1:
                    return 'bg-secondary';
                case 2:
                    return 'bg-warning';
                case 3:
                    return 'bg-success';
                case 4:
                    return 'bg-light-primary';
            }
        }
    }
    if(!function_exists('generatePaymentNo'))
    {
        //generate payment number
        function generatePaymentNo()
        {
            $paymentnumber = App\Models\InvoicePayment::Orderby('id', 'desc')->latest()->first();
            if($paymentnumber)
            {
                if($paymentnumber && $paymentnumber->voucher_no!=""){
                    /* if payment code not empty */
                    $code=$paymentnumber->voucher_no;
                    $new_code = $code + 1;
                    return $new_code;
                }else{
                    /* if payment code is empty set start */
                    return '1';
                }
            }
            return '1';
        }
    }

    if(!function_exists('getIndianCurrency'))
    {
        //get currency in words
        function getIndianCurrency(float $number)
        {
            $decimal = round($number - ($no = floor($number)), 2) * 100;
            $hundred = null;
            $digits_length = strlen($no);
            $i = 0;
            $str = array();
            $words = array(0 => '', 1 => 'one', 2 => 'two',
                3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
                7 => 'seven', 8 => 'eight', 9 => 'nine',
                10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
                16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
                19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
                40 => 'forty', 50 => 'fifty', 60 => 'sixty',
                70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
            $digits = array('', 'hundred','thousand','lakh', 'crore');
            while( $i < $digits_length ) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                } else $str[] = null;
            }

            $Rupees = implode('', array_reverse($str));
            $paise = ($decimal > 0) ? " point " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . '' : '';
            return ($Rupees ? $Rupees . '' : '') . $paise;
        }
    }

    if(!function_exists('getBrachProductCreatePriviledge'))
    {
        //get discount type
        function getBrachProductCreatePriviledge()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['allow_branches_to_create_products']))
            {
                $priviledge = (($site['allow_branches_to_create_products']) && ($site['allow_branches_to_create_products'] !=""))? $site['allow_branches_to_create_products'] : 1;
                return $priviledge;
            }
            return 1;
        }
    }

    if(!function_exists('getFrontendStatus'))
    {
        //get discount type
        function getFrontendStatus()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['frontend_enabled']))
            {
                $frontend_enabled = $site['frontend_enabled'] ?? 1;
                return $frontend_enabled;
            }
            return 1;
        }
    }

    if(!function_exists('getBrachMaterialCreatePriviledge'))
    {
        //get discount type
        function getBrachMaterialCreatePriviledge()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['allow_branches_to_create_materials']))
            {
                $priviledge = (($site['allow_branches_to_create_materials']) && ($site['allow_branches_to_create_materials'] !=""))? $site['allow_branches_to_create_materials'] : 1;
                return $priviledge;
            }
            return 1;
        }
    }
/* get printer type */
    if(!function_exists('getDefaultPrinter'))
    {
        //Application name 
        function getDefaultPrinter()
        {
            $settings = new App\Models\MasterSetting();
            $site = $settings->siteData();
            if(isset($site['default_printer']))
            {
                $currency = (($site['default_printer']) && ($site['default_printer'] !=""))? $site['default_printer'] : 1;
                return $currency;
            }
            return 1;
        }
    }

?>