<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Translation;
use App\Models\Material;
use Carbon\Carbon;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Cursor;
use Barryvdh\DomPDF\Facade\Pdf;


class StockReport extends Component
{
    public $search = '', $materials;
    use WithPagination;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    //render the page & data
    public function render()
    {
        return view('livewire.admin.reports.stock-report');
    }

    //call reload materials on query change
    public function updatedSearch()
    {
        $this->reloadMaterials();
    }

    //set materials,
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
    }

    /* refresh the page */
    public function refresh()
    {
        /* if search query or order filter is empty */
        if ($this->search == '') {
            $this->materials->refresh();
        }
    }

    //load the materials
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

    //reload materials on query change
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

    //filter data based on query
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

    //download pdf
    public function downloadFile()
    {
        $search = $this->search;
        $pdfContent = Pdf::loadView('livewire.admin.reports.downloads.stock-report',compact('search'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "Stock Report_from_" . Carbon::today()->toDateString() . ".pdf");
    }
}