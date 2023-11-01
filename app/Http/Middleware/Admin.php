<?php

namespace App\Http\Middleware;

use App\Models\MasterSetting;
use Closure;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ((Auth::check()) && ((Auth::user()->user_type=="2") || (Auth::user()->user_type=="3") && (Auth::user()->is_active==1)) ) {
            if(Session::has('selected_language'))
            {
                App::setLocale(Session::get('selected_language'));
                
            }
            return $next($request);
        }
        return redirect()->route('login');
    }
}
