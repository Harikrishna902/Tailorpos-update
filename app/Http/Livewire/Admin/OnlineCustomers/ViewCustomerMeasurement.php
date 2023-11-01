<?php

namespace App\Http\Livewire\Admin\OnlineCustomers;

use App\Models\OnlineCustomerMeasurement;
use App\Models\OnlineCustomerMeasurementDetail;
use App\Models\Measurement;
use App\Models\OnlineCustomer;
use App\Models\MeasurementDetail;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewCustomerMeasurement extends Component
{
    public $attributes,$type='',$unit,$measurements,$userattributes,$customer_id,$measurement,$notes,$customer;
    public function render()
    {
        return view('livewire.admin.online-customers.view-customer-measurement');
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
        $this->customer=OnlineCustomer::find($id);

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
        if(!OnlineCustomerMeasurement::where('customer_id',$this->customer_id)->where('measurement_id',$this->type)->first())
        {
            $measurement = new OnlineCustomerMeasurement();
            $measurement->customer_id = $this->customer_id;
            $measurement->unit = $this->unit;
            $measurement->measurement_id = $this->type;
            $measurement->notes = $this->notes;
            $measurement->save();
        }
        else{
            $measurement  = OnlineCustomerMeasurement::where('customer_id',$this->customer_id)->where('measurement_id',$this->type)->first();
            OnlineCustomerMeasurementDetail::where('customer_measurement_id',$measurement->id)->delete();
            $measurement->unit = $this->unit;
            $measurement->notes = $this->notes;
            $measurement->save();
        }
        foreach($this->userattributes as $key => $value)
        {
            $onlinemeasurementdetail = new OnlineCustomerMeasurementDetail();
            $onlinemeasurementdetail->customer_id = $this->customer_id;
            $onlinemeasurementdetail->customer_measurement_id = $measurement->id;
            $onlinemeasurementdetail->attribute_id = $key;
            $onlinemeasurementdetail->value = $value;
            $onlinemeasurementdetail->save();
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
            $measurement  = OnlineCustomerMeasurement::where('customer_id',$this->customer_id)->where('measurement_id',$this->type)->first();
            $userattributes = new Collection();
            if($measurement)
            {
                $this->notes = $measurement->notes;
                $this->unit = $measurement->unit;
                $userattributes = OnlineCustomerMeasurementDetail::where('customer_measurement_id',$measurement->id)->where('customer_id',$this->customer_id)->get();
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
