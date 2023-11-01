<?php

namespace App\Http\Livewire\Admin\Translation;

use App\Models\Translation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $translations,$search_query,$translation,$category;

    //list translations & render page
    public function render()
    {
        $translations = Translation::latest();
        if($this->search_query != '')
        {
            $translations->where('name','like','%'.$this->search_query.'%');
        }
        $this->translations = $translations->get();
        return view('livewire.admin.translation.index');
    }

    // process on mount
    public function mount()
    {
        abort_if(Auth::user()->user_type != 2,404);
    }

    //toggle translation active status
    public function toggle($id)
    {
        $translation = Translation::find($id);
        if($translation->is_active == 1)
        {
            $translation->is_active = 0;
        }
        else{
            $translation->is_active = 1;
        }
        $translation->save();
    }

    // delete confirmation 
    public function deleteConfirm($id)
    {
        $this->translation = Translation::find($id);
    }

    // delete process
    public function delete()
    {
        $category = $this->translation;
        $category->delete();
        $this->category = null;
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Translation was deleted!']);
    }
}