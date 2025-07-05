<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

/*    protected function redirectTo()
    {
        if (Auth()->user()->role === 'A') {

            return route('admin.dashboard');
        } elseif (Auth()->user()->role === 'R') {
            return route('recruiter.dashboard');
        } elseif (Auth()->user()->role === 'H') {
            return route('hod.dashboard');
        }
    }*/

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'Status' => 'A')) || auth()->attempt(array('Username' => $input['email'], 'password' => $input['password'], 'Status' => 'A'))) {

            $request->session()->put('Set_Company', $input['company']);
            $request->session()->put('Set_Country', $input['country']);
            $userId = Auth::user()->id;
            $themedetail = DB::table("theme_customizer")->where("UserId", $userId)->get();

            if (count($themedetail) > 0) {
                $request->session()->put('ThemeStyle', $themedetail[0]->ThemeStyle);
                $request->session()->put('SidebarColor', $themedetail[0]->SidebarColor);
            }
            if ($userId == '1084') {
                LogActivity::addToLog('User Login - IT Admin', 'Login');
            }else{
                LogActivity::addToLog('User Login - ' . getFullName(Auth::user()->id), 'Login');
            }
            if (auth()->user()->role == 'A') {
                return redirect()->route('admin.dashboard');
            } elseif (auth()->user()->role == 'R') {
                return redirect()->route('recruiter.dashboard');
            } elseif (auth()->user()->role == 'H') {
                return redirect()->route('hod.dashboard');
            }
        } else {
            return redirect()->route('login')->with('error', 'Email or Password are wrong...');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
