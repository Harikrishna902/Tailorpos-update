<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\FinancialYear;
use Carbon\Carbon;
use Auth;

class FinancialYearSettings extends Component
{
    public $years,$name,$start_date,$end_date,$year,$edityear,$search_query,$lang;

    /* render the page */
    public function render()
    {
        return view('livewire.admin.settings.financial-year-settings');
    }

    /* process before render */
    public function mount()
    {
        $this->years = FinancialYear::orderBy('year','DESC')->get();
        $this->start_date = Carbon::today()->month(4)->startOfMonth()->toDateString();
        $this->end_date = Carbon::today()->month(3)->endOfMonth()->addYear(1)->toDateString();
        $this->year = Carbon::today()->format('Y');
    }
    
    /* create financial year */
    public function create()
    {
        $this->validate([
            'year' => 'required',
            'start_date'    => 'required',
            'end_date'  => 'required',
        ]);
        $storeUpdate = FinancialYear::create([
            'year'  => $this->year,
            'starting_date'    => $this->start_date,
            'ending_date'  => $this->end_date
        ]);
        $storeUpdate->save();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Financial Year has been created!']);
        $this->years = FinancialYear::orderBy('year','DESC')->get();
        $this->start_date = Carbon::today()->month(4)->startOfMonth()->toDateString();
        $this->end_date = Carbon::today()->month(3)->endOfMonth()->addYear(1)->toDateString();
        $this->year = Carbon::today()->format('Y');
        $this->resetErrorBag();
    }
    /* set the content for edit */
    public function edit($id)
    {   
        $this->resetErrorBag();
        $this->edityear = FinancialYear::where('id',$id)->first();
        $this->start_date = $this->edityear->starting_date;
        $this->end_date = $this->edityear->ending_date;
        $this->year = $this->edityear->year;
    }   
    /* update financial year */
    public function update()
    {
      $this->validate([
          'year' => 'required',
          'start_date'    => 'required',
          'end_date'  => 'required',
       ]);
        /* if edityear is exists */
        if($this->edityear)
        {
            $this->edityear->starting_date = $this->start_date;
            $this->edityear->ending_date = $this->end_date;
            $this->edityear->year = $this->year;
            $this->edityear->save();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Financial Year has been updated!']);
            $this->years = FinancialYear::orderBy('year','DESC')->get();
            $this->start_date = Carbon::today()->month(4)->startOfMonth()->toDateString();
            $this->end_date = Carbon::today()->month(3)->endOfMonth()->addYear(1)->toDateString();
            $this->year = '';
        }
    }
    /* process while change the content*/
    public function updated($name,$value)
    {
        /* if the updated element is search_query */
        if($name == 'search_query' && $value != '')
        {
          $this->years = FinancialYear::orderBy('year','DESC')->where('year', 'like' , '%'.$value.'%')->get();
        }
        elseif($name == 'search_query' && $value == ''){
            $this->years = FinancialYear::orderBy('year','DESC')->get();
        }
    }
    
   /* reset input fields */
    public function resetFields()
    {
        $this->start_date = Carbon::today()->month(4)->startOfMonth()->toDateString();
        $this->end_date = Carbon::today()->month(3)->endOfMonth()->addYear(1)->toDateString();
        $this->year = Carbon::today()->format('Y');
        $this->resetErrorBag();
    }
}