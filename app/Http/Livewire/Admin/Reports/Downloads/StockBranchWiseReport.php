<?php

namespace App\Http\Livewire\Admin\Reports\Downloads;

use Livewire\Component;

class StockBranchWiseReport extends Component
{
    // render the page
    public function render()
    {
        return view('livewire..admin.reports.downloads.stock-branch-wise-report')->extends('layouts.print-layout')
            ->section('content');
    }
}