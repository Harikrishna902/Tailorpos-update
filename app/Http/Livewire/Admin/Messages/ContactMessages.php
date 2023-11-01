<?php

namespace App\Http\Livewire\Admin\Messages;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ContactMessages extends Component
{
    public $messages,$name,$is_active=1,$message,$search_query,$url,$image,$i = 0;
    public function render()
    {
        $query = ContactMessage::latest();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query->where('branch_id',Auth::user()->id);
        }
        if($this->search_query != '')
        {
            $search = $this->search_query;
            $query->where('name','like','%'.$search.'%');
        }
        $this->messages= $query->get();
        return view('livewire.admin.messages.contact-messages');
    }


    //ContactMessage toggle active
    public function changeStatus($id,$value)
    {
        $message = ContactMessage::find($id);
        $message->status = $value;
        $message->save();
    }

    public function viewNote($id)
    {
        $this->message = ContactMessage::where('id',$id)->first();
    }

    /* delete */   
    public function delete()
    {
        if(!$this->message)
        {
            return;
        }
        $this->message->delete();
        $this->message = null;
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Message has been deleted!']);
        $this->emit('closemodal');
    }
}
