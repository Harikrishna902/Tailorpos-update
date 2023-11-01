<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use Livewire\Component;
use App\Models\Material;

class PrintStockBranchWiseReport extends Component
{
    public $branch_id, $materials,$search;

    //render the page
    public function render()
    {
        return view('livewire..admin.reports.prints.print-stock-branch-wise-report')->extends('layouts.print-layout')
            ->section('content');
    }

    //apply filters and load content for print
    public function mount($branch = null,$search = null)
    {
        if($branch=='all') {
            $this->branch_id  = null;
        } else {
            $this->branch_id  = $branch;
        }
        $material = Material::latest();
        if ($search != '') {
            $material->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });

            $this->search = $search;
        }
        $this->materials = $material->get();
    }
}