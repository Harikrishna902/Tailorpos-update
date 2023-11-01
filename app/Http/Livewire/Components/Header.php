<?php

namespace App\Http\Livewire\Components;

use App\Models\Translation;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Header extends Component
{
    public $languages, $lang;

    // render the page
    public function render()
    {
        return view('livewire.components.header');
    }

    // process on mount
    public function mount()
    {
        $this->languages = [
            'en' => 'English',
            'fr' => 'French',
            'hi' => 'Hindi',
            'it' => 'Italian',
            'ru' => 'Russian',
            'es' => 'Spanish',
        ];
        if(Session::has('selected_language'))
        {
            $this->lang = Session::get('selected_language');
        }
    }

    //check if financial year is set
    public function check()
    {
        if (getFinancialYearID() == null) {
            $this->dispatchBrowserEvent(
                'swal-alert',
                ['type' => 'error', 'title' => 'Financial Year Not Set!',  'message' => 'Contact an admin!']
            );
            return 1;
        }
        return redirect()->route('admin.invoice');
    }

    //change language
    public function changeLanguage($id)
    {
        if ($id) {
            session()->put('selected_language', $id);
            $this->emit('reloadpage');
        }
        else{
            session()->put('selected_language', 'en');
            $this->emit('reloadpage');
        }
    }
}