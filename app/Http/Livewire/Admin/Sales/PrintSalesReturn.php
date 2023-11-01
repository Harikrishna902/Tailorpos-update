<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\MasterSetting;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintSalesReturn extends Component
{
    public $company_name,$salesreturn;

    //render the page & data
    public function render()
    {
        return view('livewire.admin.sales.print-sales-return')->extends('layouts.print-layout')
        ->section('content');
    }

    //load sales return
    public function mount($id)
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        $this->company_name = $site['company_name'] ?? 'Tailor POS';
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->salesreturn = SalesReturn::where('id',$id)->first();
        }
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->salesreturn = SalesReturn::where('id',$id)->where('created_by',Auth::user()->id)->first();
        }
        if(!$this->salesreturn){
            abort(404);
        }
    }
}