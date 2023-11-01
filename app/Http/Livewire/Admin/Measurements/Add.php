<?php

namespace App\Http\Livewire\Admin\Measurements;

use App\Models\Measurement;
use Livewire\Component;
use App\Models\MeasurementDetail;
use Auth;
use App\Models\Translation;

class Add extends Component
{
    public $name,$items=[],$is_active=1,$selected_attributes=[];
    
    //render the page
    public function render()
    {
        return view('livewire.admin.measurements.add');
    }

    //save measurement 
    public function save(){
        $this->validate([
            'name' => 'required',
        ]);
        $measurement = new Measurement();
        $measurement->name = $this->name;
        $measurement->is_active = $this->is_active??0;
        $measurement->created_by = Auth::user()->id;
        $measurement->save();
        if($this->selected_attributes) {
            foreach($this->selected_attributes as $key => $value)
            {
                if($value === true)
                {
                    $material_details = MeasurementDetail::create([
                        'measurement_id'=>$measurement->id,
                        'measurement_attributes_id'=>$key,
                        'created_at'=>\Carbon\Carbon::now()
                    ]);
                }
            }
        }
        $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Measurement Created Successfully!']);
        return redirect()->route('admin.measurements');
    }

    //reset input fields
    public function resetInputFields(){
        $this->name="";
    }
}