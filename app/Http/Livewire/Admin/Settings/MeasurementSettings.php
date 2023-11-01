<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\MeasurementAttribute;
use App\Models\Translation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MeasurementSettings extends Component
{
    public $items,$item,$name,$is_active=1,$search='';

    //render the page
    public function render()
    {
        $query = MeasurementAttribute::latest();
        if($this->search != '')
        {
            $search = $this->search;
            $query->where('name','like','%'.$this->search.'%');
        }
        $this->items = $query->get();
        return view('livewire.admin.settings.measurement-settings');
    }

    //validation rules
    protected $rules = [
        'name'  => 'required',
    ];

    //reset all input fields to empty
    public function resetFields()
    {
        $this->name = '';
        $this->is_active=1;
        $this->resetErrorBag();
    }

    //create measurement
    public function create()
    {
        $this->validate();
        MeasurementAttribute::create([
            'name'  => $this->name,
            'created_by'   => Auth::user()->id,
            'is_active' => $this->is_active ?? 0
        ]);
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Measurement has been created!']);
    }

    //prepare measurement for edit
    public function edit($id)
    {
        $this->resetErrorBag();
        $this->item = MeasurementAttribute::find($id);
        if($this->item)
        {
            $this->name = $this->item->name;
            $this->is_active = $this->item->is_active;
        }
    }

    //update measurement
    public function update()
    {
        $this->validate();
        if($this->item)
        {
            $this->item->name = $this->name;
            $this->item->is_active = $this->is_active;
            $this->item->save();
            $this->resetFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Measurement has been updated!']);
        }
    }

    //delete measurement
    public function delete()
    {
        $category = $this->item;
        $category->delete();
        $this->item = null;
        $this->emit('closemodal');
    }

    //toggle measurement active status
    public function toggle($id)
    {
        $measurement = MeasurementAttribute::find($id);
        if($measurement->is_active == 1)
        {
            $measurement->is_active = 0;
        }
        else{
            $measurement->is_active = 1;
        }
        $measurement->save();
    }

    //delete confirmation
    public function deleteConfirm($id)
    {
        $this->item = MeasurementAttribute::find($id);
    }
}