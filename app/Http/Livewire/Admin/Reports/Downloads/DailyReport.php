<?php

namespace App\Http\Livewire\Admin\Reports\Downloads;

use Livewire\Component;

class DailyReport extends Component
{
    // render the page
    public function render()
    {
        return view('livewire.admin.reports.downloads.daily-report')->extends('layouts.print-layout')
        ->section('content');
    }
}