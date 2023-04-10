<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\Models\User;
use App\Models\Customer;
use App\Models\Cart;
// Code cange by Brijesh on 23-fab-22 CR#2 - start
use App\Models\Wishlist;
// Code cange by Brijesh on 23-fab-22 CR#2 - end
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

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
    * Get the needed authorization credentials from the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    protected function credentials(Request $request)
    { 
        if($request->get('phone') != null){
            return ['phone'=>"+{$request['country_code']}{$request['phone']}", 'password'=>$request->get('password')];
        }
        elseif($request->get('email') != null){
            return $request->only($this->username(), 'password');
        }
    }

    /**
     * Check user's role and redirect user based on their role
     * @return
     */
    public function authenticated()
    {
        if(session('temp_user_id') != null){
            Cart::where('temp_user_id', session('temp_user_id')) 
                    ->update(
                            [
                                'user_id' => auth()->user()->id,
                                'temp_user_id' => null
                            ]
            );

            Session::forget('temp_user_id');
        }

        // Code cange by Brijesh on 23-fab-22 CR#2 - start
        if(session('temp_wish_id') != null){
            Wishlist::where('temp_wish_id', session('temp_wish_id'))
                    ->update([
                                'user_id' => auth()->user()->id,
                                'temp_wish_id' => null
                            ]);

            Session::forget('temp_wish_id');
        }
		// Code cange by Brijesh on 23-fab-22 CR#2 - end
        
        if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')
        {
            return redirect()->route('admin.dashboard');
        } else {

            if(session('link') != null){
                return redirect(session('link'));
            }
            else{
                return redirect()->route('home');
            }
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(translate('Invalid login credentials'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if(auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')){
            $redirect_route = 'login';
        }
        else{
            $redirect_route = 'home';
        }
        
        //User's Cart Delete
        if(auth()->user()){
            Cart::where('user_id', auth()->user()->id)->delete();
        }
        
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
