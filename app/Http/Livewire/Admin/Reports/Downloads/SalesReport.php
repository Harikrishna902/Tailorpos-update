<?php

namespace App\Http\Livewire\Admin\Reports\Downloads;

use Livewire\Component;

class SalesReport extends Component
{
    // render the page
    public function render()
    {
        return view('livewire.admin.reports.downloads.sales-report')->extends('layouts.print-layout')
        ->section('content');
    }
}