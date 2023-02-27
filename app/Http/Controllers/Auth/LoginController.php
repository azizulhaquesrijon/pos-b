<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'pin' => 'required',
        ]);
    }

    public function signin(Request $request)
    {

        $data = $pin = $request->only('pin');
        $data['mobile'] = $mobile = $request->mobile;

        $user = User::where('pin', $pin)->where('mobile', $mobile)->orWhere('email', $mobile)->first();
        // dd($user);
        if (!$user) {
            return back()->with('message', 'Number Or password is wrong');
        }
        Auth::login($user);

        return redirect()->route('home');
    }
}
