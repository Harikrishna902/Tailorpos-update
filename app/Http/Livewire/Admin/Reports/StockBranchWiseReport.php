<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Translation;
use App\Models\Material;
use App\Models\User;
use Livewire\WithPagination;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use Barryvdh\DomPDF\Facade\Pdf;

class StockBranchWiseReport extends Component
{
    public $search, $branches, $branch_id, $materials;
    use WithPagination;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    //render the page & data
    public function render()
    {
        return view('livewire.admin.reports.stock-branch-wise-report');
    }

    //reload materials when search is updated
    public function updatedSearch()
    {
        $this->reloadMaterials();
    }

    //set default materials,branch
    public function mount()
    {
        $this->materials = new Collection();
        $this->loadMaterials();
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
        /* if the user is branch */
        $this->branches = User::where('user_type', 3)->latest()->get();
        /* if the user is not admin */
        if (Auth::user()->user_type != 2) {
            $this->branch_id = Auth::user()->id;
        }
    }

    /* refresh the page */
    public function refresh()
    {
        /* if search query or order filter is empty */
        if ($this->search == '') {
            $this->materials->fresh();
        }
    }

    //load materials
    public function loadMaterials()
    {
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $myorder = $this->filterdata();
        $this->materials->push(...$myorder->items());
        if ($this->hasMorePages = $myorder->hasMorePages()) {
            $this->nextCursor = $myorder->nextCursor()->encode();
        }
        $this->currentCursor = $myorder->cursor();
    }

    //reload material son query change
    public function reloadMaterials()
    {
        $this->materials = new Collection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->materials->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    //filter data based in query
    public function filterdata()
    {
        $material = Material::latest();
        if ($this->search != '') {
            $search = $this->search;
            $material->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%');
            });
        }
        $materials  = $material->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $materials;
    }

    /* download pdf file */
    public function downloadFile()
    {
        $branch_id = $this->branch_id;
        $search = $this->search;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.stock-branch-wise-report', compact('branch_id','search'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "StockwiseBranch_report.pdf");
    }
}