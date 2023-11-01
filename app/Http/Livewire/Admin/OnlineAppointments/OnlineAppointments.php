<?php

namespace App\Http\Livewire\Admin\OnlineAppointments;

use App\Models\OnlineAppointment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class OnlineAppointments extends Component
{
    use WithFileUploads;
    public $appointments,$name,$is_active=1,$appointment,$search_query,$url,$image,$i = 0;
    public function render()
    {
        $query = OnlineAppointment::latest();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query->where('branch_id',Auth::user()->id);
        }
        if($this->search_query != '')
        {
            $search = $this->search_query;
            $query->whereHas('customer',function($query2) use ($search) {
                $query2->where('name','like','%'.$search.'%');
            });
        }
        $this->appointments= $query->get();
        return view('livewire.admin.online-appointments.online-appointments');
    }


    //OnlineAppointment toggle active
    public function changeStatus($id,$value)
    {
        $appointment = OnlineAppointment::find($id);
        $appointment->status = $value;
        $appointment->save();
    }

    public function viewNote($id)
    {
        $this->appointment = OnlineAppointment::where('id',$id)->first();
    }


    /* delete */   
    public function delete()
    {
        if(!$this->appointment)
        {
            return;
        }
        $this->appointment->delete();
        $this->appointment = null;
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Appointment has been deleted!']);
        $this->emit('closemodal');
    }
}
