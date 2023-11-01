<?php

namespace App\Http\Livewire\Admin\Translation;

use App\Models\Translation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Add extends Component
{
    public $data=[],$default,$name,$is_active=1,$is_rtl = 0;

    //render the page
    public function render()
    {
        return view('livewire.admin.translation.add');
    }

    //load translation from global.php
    public function mount()
    {
        abort_if(Auth::user()->user_type != 2,404);
        foreach(config('global.translation.section') as $value)
        {
            foreach($value['values'] as $key => $default)
            {
                $this->data[$key] = $default;
            }
        }
    }

    /* save the content */
    public function save()
    {
        $this->validate([
            'name'  => 'required',
            'data.*' => 'required'
        ]);
        if($this->default)
        {
            Translation::where('default',1)->update([
                'default'=> 0]
            );
        }
        if($this->is_rtl == null || !$this->is_rtl)
        {
            $this->is_rtl = 0;
        }
        Translation::create([
            'name'  => $this->name,
            'is_active' => $this->is_active,
            'default'   => $this->default,
            'data'  => $this->data,
            'is_rtl'    => $this->is_rtl,
        ]);
        return redirect()->route('admin.translation');
    }
}