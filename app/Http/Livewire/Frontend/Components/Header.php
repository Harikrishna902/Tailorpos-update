<?php

namespace App\Http\Livewire\Frontend\Components;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Header extends Component
{
    public $branches,$branch, $languages, $lang;
    //render component
    public function render()
    {
        return view('livewire.frontend.components.header');
    }

    //load default branches and set branch
    public function mount()
    {
        $this->branches = User::whereIn('user_type',[2,3])->get();
        $this->branches->setVisible(['name','id']);
        if(count($this->branches) == 0)
        {
            return;
        }
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
        $this->branch->setVisible(['name','id']);

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
        }else{
            $this->lang = 'en';
        }
    }

    //change branch
    public function changeBranch($branch)
    {
        $this->branch = User::whereId($branch)->first();
        $this->branch->setVisible(['name','id']);
        Session::put('current_branch',$this->branch->id);
        $this->emit('branchChanged',$this->branch->id);
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

    //logout
    public function logout()
    {
        Auth::guard('customer')->logout();
        Session::flush();
        
        $this->emit('reloadpage');
        return redirect()->route('frontend');
    }
}