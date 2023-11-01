<?php

namespace App\Http\Livewire\Frontend\Profile;

use App\Models\OnlineCustomer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Image;

class EditProfile extends Component
{
    public $name,$email,$phone,$password,$profileImage;
    use WithFileUploads;
    public function render()
    {
        return view('livewire.frontend.profile.edit-profile')->layout('layouts.frontend');
    }

    public function mount()
    {
        $user = Auth::guard('customer')->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function save()
    {
        $this->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:online_customers,email,'.Auth::guard('customer')->user()->id,
            'phone' => 'required'
        ]);
        $user = OnlineCustomer::whereId(Auth::guard('customer')->user()->id)->first();
        if($this->password)
        {
            $user->password = Hash::make($this->password);
        }
        if($this->profileImage){
            $image = $this->profileImage;
            $input['file'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $imgFile = Image::make($this->profileImage->getRealPath());
            $imgFile->save($destinationPath.'/'.$input['file'],75,'jpg');
            $user->avatar = '/uploads/profile/'.$input['file'];
        }
        $user->name =$this->name;
        $user->email =$this->email;
        $user->phone =$this->phone;
        $user->save();
        $this->dispatchBrowserEvent('send-notification',['title' => 'Success',  'message' => 'Your profile has been updated!']);
    }
}