<?php

namespace App\Http\Livewire\Admin\Invoice;

use Livewire\Component;

class DownloadPdfA4 extends Component
{
    public function render()
    {
        return view('livewire.admin.invoice.download-pdf-a4')->extends('layouts.print-layout')
        ->section('content');
    }
}