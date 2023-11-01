<?php

namespace App\Http\Livewire\Admin\Sliders;

use App\Models\Slider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Image;
use Livewire\WithFileUploads;

class Sliders extends Component
{
    public $sliders,$name,$is_active=1,$slider,$search_query,$url,$image,$i = 0;
    use WithFileUploads;
    public function render()
    {
        $query = Slider::latest();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query->where('created_by',Auth::user()->id);
        }
        if($this->search_query != '')
        {
            $query->where('name','like','%'.$this->search_query.'%');
        }
        $this->sliders= $query->get();
        return view('livewire.admin.sliders.sliders');
    }

    //Create Slider
    public function create()
    {
        $this->validate([
            'name'  => 'required',
            'image' => 'required',
        ]);
        $slider = new Slider();
        $slider->name = $this->name;
        $slider->is_active = $this->is_active;
        $slider->url = $this->url;
        $slider->created_by = Auth::user()->id;
        if($this->image){
            $input['file'] = time().'.jpg';
            $destinationPath = public_path('/uploads/sliders');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $imgFile = Image::make($this->image->getRealPath());
            $imgFile->save($destinationPath.'/'.$input['file'],75,'jpg');
            $imageurl = '/uploads/sliders/'.$input['file'];
            $slider->image = $imageurl;
        }
        $slider->save();
        $this->resetFields();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->sliders = Slider::where('created_by',Auth::user()->id)->latest()->get();
        }
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->sliders = Slider::latest()->get();
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Slider has been created!']);
        $this->emit('closemodal');
    }
    /* set content to edit */   
    public function edit($id)
    {
        $this->slider = Slider::where('id',$id)->first();
        $this->is_active = $this->slider->is_active;
        $this->name = $this->slider->name;
        $this->url = $this->slider->url;
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->sliders = Slider::where('created_by',Auth::user()->id)->latest()->get();
        }
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->sliders = Slider::latest()->get();
        }
        $this->resetErrorBag();
    }
    /* update the servicetype */
    public function update()
    {   /* if service type is exist */
        $this->validate([
            'name'  => 'required'
        ]);
        if($this->slider)
        {
            try{
                unlink(public_path($this->slider->image));
            }
            catch(\Exception $e)
            {
               
            }
            $this->slider->name = $this->name;
            $this->slider->is_active = $this->is_active;
            if($this->image){
                $input['file'] = time().'.jpg';
                $destinationPath = public_path('/uploads/sliders');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $imgFile = Image::make($this->image->getRealPath());
                $imgFile->save($destinationPath.'/'.$input['file'],75,'jpg');
                $imageurl = '/uploads/sliders/'.$input['file'];
                $this->slider->image = $imageurl;
            }
            $this->slider->save();
        }
        $this->resetFields();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Slider has been updated!']);
        $this->emit('closemodal');
    }

    //reset input fields
    public function resetFields()
    {
        $this->name = null;
        $this->url = null;
        $this->is_active = 1;
        $this->image = "";
        $this->i= $this->i+1;
        $this->resetErrorBag();
    }

    //Slider toggle active
    public function toggle($id)
    {
        $slider = Slider::find($id);
        if($slider->is_active == 1)
        {
            $slider->is_active = 0;
        }
        else{
            $slider->is_active = 1;
        }
        $slider->save();
    }


    /* set content to delete */   
    public function deleteConfirm($id)
    {
        $this->slider = Slider::where('id',$id)->first();
    }

    /* delete */   
    public function delete()
    {
        try{
            unlink(public_path($this->slider->image));
        }
        catch(\Exception $e)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Failed to delete slider image!']);
        }
        $this->slider->delete();
        $this->slider = null;
      
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Slider has been deleted!']);
        $this->emit('closemodal');
    }
}
