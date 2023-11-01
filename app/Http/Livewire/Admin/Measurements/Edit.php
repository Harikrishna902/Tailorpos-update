<?php

namespace App\Http\Livewire\Admin\Measurements;

use Livewire\Component;
use App\Models\MeasurementAttribute;
use App\Models\Measurement;
use App\Models\MeasurementDetail;
use App\Models\Translation;

class Edit extends Component
{
    public $name,$is_active=1,$selected_attributes=[],$previous_attributes=[],$edit_id,$measurement;

    //render the page
    public function render()
    {
        return view('livewire.admin.measurements.edit');
    }

    //load attributes for edit
    public function mount($id){    
      $this->edit_id = $id;
      $attributes = MeasurementDetail::where('measurement_id',$id)->get();
      foreach($attributes as $row){
        array_push($this->selected_attributes,$row->measurement_attributes_id);
      }
      $measurement = Measurement::findorfail($id);
      $this->measurement = $measurement;
      $this->name= $measurement->name;
      $this->is_active= $measurement->is_active;
    }

    //save measurement and its attributes
    public function save(){
        $this->validate([
            'name' => 'required',
        ]);
        $measurement = Measurement::find($this->edit_id);
        $attributes = MeasurementDetail::where('measurement_id',$this->edit_id)->get();
        $myarray = [];
        $myarray2 = [];
        foreach($attributes as $item)
        {
            $myarray[$item->measurement_attributes_id] = collect($this->selected_attributes)->contains(function ($value, $key) use($item) {
                return $value == $item->measurement_attributes_id;
            });

            if($myarray[$item->measurement_attributes_id] == false)
            {
                $item->delete();
            }
        }
        foreach($this->selected_attributes as $key => $item)
        {
            $myarray2[$item] = collect($myarray)->search(function ($value, $key2) use($item) {
                return $key2 == $item;
            });
        }
            foreach($myarray2 as $key => $value)
            {
                if($value == false)
                {
                    $material_details = MeasurementDetail::create([
                        'measurement_id'=>$measurement->id,
                        'measurement_attributes_id'=>$key,
                    ]);
                }
            }
        $measurement->name = $this->name;
        $measurement->is_active = $this->is_active??0;
        $measurement->save();
        $this->emit('reloadpage');
        $this->emit('savemessage',['type' => 'success','title' => 'Success','message' => 'Measurement Updated Successfully!']);
        return redirect()->route('admin.measurements');
    }

    //reset input fields
    public function resetInputFields(){
        $this->name="";
    }
}