<?php

namespace App\Http\Livewire\Admin\Stock;

use Livewire\Component;
use Auth;
use App\Models\StockTransfer;
use App\Models\Translation;
use App\Models\User;

class StockTransferList extends Component
{

    public $stocks, $search, $branches, $branch_id,$transfer;

    //render the page and list stock transfers
    public function render()
    {
        $query = StockTransfer::where('created_by', Auth::user()->id)->latest();
        if ($this->search != '') {
            $value = $this->search;
            $query->whereHas('branch', function ($query1) use ($value) {
                $query1->where('name', 'LIKE', '%' . $value . '%');
            });
        }
        if ($this->branch_id != '') {
            $value = $this->branch_id;
            $query->whereHas('branch', function ($query1) use ($value) {
                $query1->where('id', $value);
            });
        }
        $this->stocks = $query->get();
        return view('livewire.admin.stock.stock-transfer-list');
    }

    //load branches
    public function mount()
    {
        if (session()->has('selected_language')) {   /*if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
        $this->branches = User::where('user_type', 3)->where('is_active', 1)->latest()->get();
    }

    /* update value while change input fields */
    public function updated($name, $value)
    {
        if ($name == "branch_id" && $value != '') {
            $this->branch_id = $value;
        }
        if ($name == "branch_id" && $value == '') {
            $this->branch_id = '';
        }
    }

    /* delete confirmation */
    public function deleteConfirm($id)
    {
        $this->transfer = StockTransfer::find($id);
    }

    /* delete process */
    public function delete()
    {
        if ($this->transfer) {
            $stocks = \App\Models\StockTransferDetail::where('stock_transfer_id', $this->transfer->id)->latest()->get();
            foreach ($stocks as $row) {
                $row->delete();
            }
            $this->transfer->delete();
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'success',  'message' => 'Stock Transfer Was Deleted!']
            );
            $this->emit('closemodal');
        }
    }
}