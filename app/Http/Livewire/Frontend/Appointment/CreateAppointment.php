<?php

namespace App\Http\Livewire\Frontend\Appointment;

use App\Models\OnlineAppointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CreateAppointment extends Component
{

    public $date,$notes,$branch;
    public function render()
    {
        return view('livewire.frontend.appointment.create-appointment')->layout('layouts.frontend');
    }

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d H:i:s');
        $session_branch = Session::get('current_branch');
        if($session_branch)
        {
            $this->branch = User::whereIn('user_type',[2,3])->whereId($session_branch)->first();
            if(!$this->branch)
            {
                $this->branch = User::whereUserType(2)->first();
            }
        }
        else{
            $this->branch = User::whereUserType(2)->first();
            Session::put('current_branch',$this->branch->id);
        }
    }

    public function save()
    {
        $todayDate = Carbon::now();
        $this->validate([
            'date'  => 'required|after:'.$todayDate
        ]);
        $appointment = new OnlineAppointment();
        $appointment->date = $this->date;
        $appointment->notes = $this->notes;
        $appointment->customer_id = Auth::guard('customer')->user()->id;
        $appointment->branch_id = $this->branch->id;
        $appointment->status = 0;
        $appointment->save();
        return true;
    }

    
}
