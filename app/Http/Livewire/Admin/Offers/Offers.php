<?php

namespace App\Http\Livewire\Admin\Offers;

use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Image;
use Livewire\WithFileUploads;

class Offers extends Component
{
    use WithFileUploads;
    public $offers,$name,$is_active=1,$offer,$search_query,$url,$image,$i = 0;
    public function render()
    {
        $query = Offer::latest();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $query->where('created_by',Auth::user()->id);
        }
        if($this->search_query != '')
        {
            $query->where('name','like','%'.$this->search_query.'%');
        }
        $this->offers= $query->get();
        return view('livewire.admin.offers.offers');
    }


    //Create Offer
    public function create()
    {
        $this->validate([
            'name'  => 'required',
            'image' => 'required',
        ]);
        $offer = new Offer();
        $offer->name = $this->name;
        $offer->is_active = $this->is_active;
        $offer->url = $this->url;
        $offer->created_by = Auth::user()->id;
        if($this->image){
            $input['file'] = time().'.jpg';
            $destinationPath = public_path('/uploads/offers');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $imgFile = Image::make($this->image->getRealPath());
            $imgFile->save($destinationPath.'/'.$input['file'],75,'jpg');
            $imageurl = '/uploads/offers/'.$input['file'];
            $offer->image = $imageurl;
        }
        $offer->save();
        $this->resetFields();
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->offers = Offer::where('created_by',Auth::user()->id)->latest()->get();
        }
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->offers = Offer::latest()->get();
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Offer has been created!']);
        $this->emit('closemodal');
    }
    /* set content to edit */   
    public function edit($id)
    {
        $this->offer = Offer::where('id',$id)->first();
        $this->is_active = $this->offer->is_active;
        $this->name = $this->offer->name;
        $this->url = $this->offer->url;
        
        /* if the user is branch */
        if(Auth::user()->user_type==3) {
            $this->offers = Offer::where('created_by',Auth::user()->id)->latest()->get();
        }
        /* if the user is admin */
        if(Auth::user()->user_type==2) {
            $this->offers = Offer::latest()->get();
        }
        $this->resetErrorBag();
    }
    /* update the servicetype */
    public function update()
    {   /* if service type is exist */
        $this->validate([
            'name'  => 'required'
        ]);
        if($this->offer)
        {
            try{
                unlink(public_path($this->offer->image));
            }
            catch(\Exception $e)
            {
               
            }
            $this->offer->name = $this->name;
            $this->offer->is_active = $this->is_active;
            if($this->image){
                $input['file'] = time().'.jpg';
                $destinationPath = public_path('/uploads/offers');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $imgFile = Image::make($this->image->getRealPath());
                $imgFile->save($destinationPath.'/'.$input['file'],75,'jpg');
                $imageurl = '/uploads/offers/'.$input['file'];
                $this->offer->image = $imageurl;
            }
            $this->offer->save();
        }
        $this->resetFields();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Offer has been updated!']);
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

    //Offer toggle active
    public function toggle($id)
    {
        $offer = Offer::find($id);
        if($offer->is_active == 1)
        {
            $offer->is_active = 0;
        }
        else{
            $offer->is_active = 1;
        }
        $offer->save();
    }

    /* set content to delete */   
    public function deleteConfirm($id)
    {
        $this->offer = Offer::where('id',$id)->first();
    }

    /* delete */   
    public function delete()
    {
        try{
            unlink(public_path($this->offer->image));
        }
        catch(\Exception $e)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Failed to delete offer image!']);
        }
        $this->offer->delete();
        $this->offer = null;
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Offer has been deleted!']);
        $this->emit('closemodal');
    }
}