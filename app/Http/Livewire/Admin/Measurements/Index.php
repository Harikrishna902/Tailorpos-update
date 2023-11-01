<?php

namespace App\Http\Livewire\Admin\Measurements;

use Livewire\Component;
use App\Models\Measurement;
use Auth;
use App\Models\Translation;

class Index extends Component
{
    public $measurements,$search;

    //render the page
    public function render()
    {
        /* if the user is not admin */
        if(Auth::user()->user_type!=2){
            abort(404);
        }
        $query = Measurement::latest();
        if($this->search != '')
        {
            $query->where('name','like','%'.$this->search.'%');
        }
        $this->measurements = $query->get();
        return view('livewire.admin.measurements.index');
    }

    //toggle is active status of measurement
    public function toggle($id)
    {
        $measurement = Measurement::find($id);
        $measurement->is_active = !($measurement->is_active);
        $measurement->save();
    }
}