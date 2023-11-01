<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class Updater extends Component
{
    public $complete = false;
    public function render()
    {
        return view('livewire.updater')->layout('layouts.updater');
    }

    //Check if install file is found, if not redirect
    public function mount()
    {
        $installFile = File::exists(base_path('update'));
        if (!$installFile) {
            return redirect('');
        }
    }

    //install : run db migrations
    public function update()
    {
        Artisan::call('migrate');
        Artisan::call('optimize');
        Artisan::call('config:cache');
        File::delete(base_path('update'));
        $this->complete = true;
        return true;
    }

    //auto loredirect to dashboard
    public function goToDashboard()
    {
        return redirect()->route('login');
    }
}