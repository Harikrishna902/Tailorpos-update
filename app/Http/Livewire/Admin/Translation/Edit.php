<?php

namespace App\Http\Livewire\Admin\Translation;

use App\Models\Translation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public $data =[],$name,$is_active=1,$default,$translation,$is_rtl;

    public function render()
    {
        return view('livewire.admin.translation.edit');
    }

    //load translation key pair from database
    public function mount($id)
    {
        abort_if(Auth::user()->user_type != 2,404);
        $translation = Translation::where('id',$id)->first();
        /* if translation is not empty */
        if(!$translation)
        {
            abort(404);
        }
        $this->data = $translation->data;
        $this->name = $translation->name;
        $this->is_active = $translation->is_active;
        $this->default = $translation->default;
        $this->translation = $translation;
        $this->is_rtl = $translation->is_rtl;
    }

    /* save the content */
    public function save()
    {
        $this->validate([
            'name'  => 'required',
            'data.*' => 'required'
        ]);
        if($this->default && $this->translation->default == 0)
        {
            Translation::where('default',1)->update([
                'default'=> 0]
            );
        }
        /* if active is 0 */
        if(!$this->is_active)
        {
            $this->default = 0;
        }
        if($this->is_rtl == null || !$this->is_rtl)
        {
            $this->is_rtl = 0;
        }
        $this->translation->name = $this->name;
        $this->translation->data = $this->data;
        $this->translation->is_active = $this->is_active;
        $this->translation->default = $this->default;
        $this->translation->is_rtl = $this->is_rtl;
        $this->translation->save();
        return redirect()->route('admin.translation');
    }
}