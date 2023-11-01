<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\ContactMessage;
use Livewire\Component;

class ContactUs extends Component
{
    public $name,$last_name,$email,$phone,$message;
    public function render()
    {
        return view('livewire.frontend.pages.contact-us')->layout('layouts.frontend');
    }

    public function save()
    {
        $this->validate([
            'name'  => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message'   => 'required'
        ]);
        $contact = new ContactMessage();
        $contact->name = $this->name;
        $contact->last_name = $this->last_name;
        $contact->email = $this->email;
        $contact->phone = $this->phone;
        $contact->message = $this->message;
        $contact->status = 0;
        $contact->save();

        $this->dispatchBrowserEvent('send-notification',['title' => 'Success',  'message' => 'Your message has been sent!']);
    }
}
