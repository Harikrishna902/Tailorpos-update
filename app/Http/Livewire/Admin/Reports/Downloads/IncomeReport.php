<?php

namespace App\Http\Livewire\Admin\Reports\Downloads;

use Livewire\Component;

class IncomeReport extends Component
{
    // render the page
    public function render()
    {
        return view('livewire.admin.reports.downloads.income-report')->extends('layouts.print-layout')
        ->section('content');
    }
}