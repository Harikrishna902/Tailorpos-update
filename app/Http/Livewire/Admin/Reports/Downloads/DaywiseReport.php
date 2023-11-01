<?php

namespace App\Http\Livewire\Admin\Reports\Downloads;

use Livewire\Component;

class DaywiseReport extends Component
{
    //render the page
    public function render()
    {
        return view('livewire.admin.reports.downloads.daywise-report')->extends('layouts.print-layout')
        ->section('content');
    }
}