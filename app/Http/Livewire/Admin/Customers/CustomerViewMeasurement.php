<?php

namespace App\Http\Livewire\Admin\Customers;

use App\Models\CustomerMeasurement;
use App\Models\CustomerMeasurementDetail;
use App\Models\Measurement;
use App\Models\Customer;
use App\Models\MeasurementDetail;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Auth;
use App\Models\Translation;

class CustomerViewMeasurement extends Component
{
    public $attributes,$type='',$unit,$measurements,$userattributes,$customer_id,$measurement,$notes,$customer;
    
    //Render the page
    public function render()
    {
        return view('livewire.admin.customers.customer-view-measurement');
    }

    //Validation rules
    protected $rules = [
        'userattributes.*' => 'required',
        'type'  => 'required',
        'unit'  => 'required'
    ];

    //Load customer measurements
    public function mount($id)
    {
        $this->customer_id = $id;
        $this->measurements = Measurement::where('is_active',1)->get();
        if(Auth::user()->user_type==2) {
            $this->customer=Customer::find($id);
        }
       /* if the user is branch */
       if(Auth::user()->user_type==3) {
            $this->customer=Customer::where('created_by',Auth::user()->id)->where('id',$id)->first();
        }
        if(!$this->customer)
        {
            abort(404);
        }
    }

    //save customer measurements
    public function save()
    {
        $this->validate();
        $measurement = null;
        if(!CustomerMeasurement::where('customer_id',$this->customer_id)->where('measurement_id',$this->type)->first())
        {
            $measurement = CustomerMeasurement::create([
                'customer_id'   => $this->customer_id,
                'measurement_id'    => $this->type,
                'unit' => $this->unit,
                'notes' => $this->notes
            ]);
        }
        else{
            $measurement  = CustomerMeasurement::where('customer_id',$this->customer_id)->where('measurement_id',$this->type)->first();
            CustomerMeasurementDetail::where('customer_measurement_id',$measurement->id)->delete();
            $measurement->unit = $this->unit;
            $measurement->notes = $this->notes;
            $measurement->save();
        }
        foreach($this->userattributes as $key => $value)
        {
            CustomerMeasurementDetail::create([
                'customer_id'   => $this->customer_id,
                'customer_measurement_id'  => $measurement->id,
                'attribute_id'  => $key,
                'value' => $value,
            ]);
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Measurement Saved!']);
    }

    //Load measurement data based on the product selected.
    public function updatedType($value)
    {
        if($this->type != '')
        {
            $this->userattributes = [];
            $measurement  = CustomerMeasurement::where('customer_id',$this->customer_id)->where('measurement_id',$this->type)->first();
            $userattributes = new Collection();
            if($measurement)
            {
                $this->notes = $measurement->notes;
                $this->unit = $measurement->unit;
                $userattributes = CustomerMeasurementDetail::where('customer_measurement_id',$measurement->id)->where('customer_id',$this->customer_id)->get();
            }
            else{
                $this->notes = '';
                $this->unit = '';
            }
            $this->attributes = MeasurementDetail::where('measurement_id',$this->type)->get();
            foreach($this->attributes as $row)
            {
                if($userattributes->where('attribute_id',$row->id)->first())
                {
                    $this->userattributes[$row->id] = $userattributes->where('attribute_id',$row->id)->first()->value;
                }
                else{
                    $this->userattributes[$row->id] = '';
                }
            }
        }
        if($this->type == '')
        {
            $this->attributes = null;
        }
    }
}