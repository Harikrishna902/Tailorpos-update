<?php

namespace App\Http\Livewire\Frontend;

use App\Models\MasterSetting;
use App\Models\OnlineCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Login extends Component
{
    public $email, $password, $success = false, $forget_password = 0;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    //check if forget password is enabled
    public function mount()
    {
        $settings = new MasterSetting();
        $site = $settings->siteData();
        if (isset($site['forget_password_enable'])) {
            if ($site['forget_password_enable'] == 0) {
            } else {
                $this->forget_password = 1;
            }
        }
        if(Auth::guard('customer')->user())
        {
            return redirect()->route('frontend');
        }
    }

    public function render()
    {
        return view('livewire.frontend.login')->layout('layouts.frontend');
    }


    //login 
    public function login()
    {
        $this->validate();
        /* user type is admin or branch */
        if ((Auth::guard('customer')->attempt(['email' => $this->email, 'password' => $this->password,'is_active' => 1]))) {
            /* user type admin and login is successful */
            return redirect()->route('frontend');
        } else {
            /* if the credentials are incorrect */
            $this->addError('login_error','Email / Password is incorrect');
        }
    }

    //Process Forgot Password
    public function forgotpassword()
    {
        if ($this->forget_password == 1) {
            $this->validate([
                'email' => 'required|email',
            ]);
            $user = OnlineCustomer::where('email', $this->email)->first();
            if ($user) {
                $token = \Illuminate\Support\Str::random(60);
                DB::table('password_resets')->where('email', $this->email)->delete();
                DB::table('password_resets')->insert([
                    'email' => $this->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                $link = url('reset-password/' . $token);
                $data = [
                    'name'  => $user->name,
                    'link'  => $link,
                ];
                try {
                    Mail::to($user->email)->send(new \App\Mail\ForgotPassword($data));
                } catch (\Exception $e) {
                    $this->addError('login_error', 'Failed to send mail, Contact an Admin.');
                    return 1;
                }
                $this->success = true;
            } else {
                $this->addError('login_error', 'No Accounts are registered with this email.');
                return 1;
            }
        }
    }
}
