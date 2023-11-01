<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\Material;
use Livewire\Component;

class PrintStockReport extends Component
{
    public $search = '', $materials;

    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-stock-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($search = '')
    {
        if($search != '')
        {
            $this->search = $search;
            $this->materials =Material::where('name','like','%'.$this->search.'%')->whereIsActive(1)->get();
        }
        else{
            $this->materials =Material::whereIsActive(1)->get();
        }
    }
}