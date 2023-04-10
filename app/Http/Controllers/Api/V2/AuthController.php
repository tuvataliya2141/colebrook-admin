<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\OTPVerificationController;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\AppEmailVerificationNotification;
use Hash;


class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if (User::where('email', $request->email_or_phone)->orWhere('phone', $request->email_or_phone)->first() != null) {
            return response()->json([
                'result' => false,
                'message' => translate('User already exists.'),
                'user_id' => 0
            ], 201);
        }

        if ($request->register_by == 'email') {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email_or_phone,
                'password' => bcrypt($request->password),
                'verification_code' => rand(100000, 999999)
            ]);
        } else if ($request->register_by == 'phone'){
            $user = new User([
                'name' => $request->name,
                'phone' => $request->email_or_phone,
                'password' => bcrypt($request->password),
                'verification_code' => rand(100000, 999999)
            ]);
        }else{
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'verification_code' => rand(100000, 999999)
            ]); 
        }
        // dd($user);
        if ($request->register_by == 'email') {
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            } else{
                try {
                    $user->notify(new AppEmailVerificationNotification());
                } catch (\Exception $e) {
                    
                }
            }
        }
        
        $user->save();
        if(isset($request->tempuserid)){
            $tempuserid = $request->tempuserid;
            Cart::where('temp_user_id', $tempuserid)
            ->update([
                        'user_id' => $user->id,
                        'temp_user_id' => null
                    ]);
        }
        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();

        if(isset($request->status)){
            $request->status = 1;
        }
        return response()->json([
            'result' => true,
            'message' => translate('Registration Successful. Please verify and log in to your account.'),
            'user_id' => $user->id,
            'user' => $user,
            'pagestatus' => $request->status
        ], 201);
    }

    public function confirmCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user->verification_code == $request->verification_code) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_code = null;
            $user->save();
            return response()->json([
                'result' => true,
                'message' => translate('Your account is now verified.Please login'),
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('Code does not match, you can request for resending the code'),
            ], 200);
        }
    }

    public function login(Request $request)
    {
        $user = User::whereIn('user_type', ['customer'])->where('email', $request->email)->orWhere('phone', $request->email)->first();   

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                $tokenResult = $user->createToken('Personal Access Token');
                if(isset($request->tempuserid)){
                    $tempuserid = $request->tempuserid;
                    Cart::where('temp_user_id', $tempuserid)
                    ->update([
                                'user_id' => $user->id,
                                'temp_user_id' => null
                            ]);
                }
                return $this->loginSuccess($tokenResult, $user);
            } else {
                return response()->json(['result' => false, 'message' => translate('Unauthorized'), 'user' => null], 401);
            }
        } else {
            return response()->json(['result' => false, 'message' => translate('User not found'), 'user' => null], 401);
        }
    }

    // public function facebookLogin(Request $request) {
    //     dd($request->all());
    //     $accessToken = $request->input('access_token');
    //     try {
    //         $fb = new \Facebook\Facebook([
    //             'app_id' => env('FACEBOOK_CLIENT_ID'),
    //             'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
    //             'default_graph_version' => 'v11.0',
    //         ]);

    //         $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);

    //         $user = $response->getGraphUser();

    //         // authenticate the user and return a JWT token
    //         // ...

    //     } catch (\Facebook\Exception\ResponseException $e) {
    //         // handle the Facebook API error
    //     }
    // }

    public function googleLogin(Request $request) {
        $access_token = $request->input('access_token');
        $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($access_token);
        if ($payload) {
            $email = $payload['email'];
            $name = $payload['name'];
            // Check if user with this email exists in your database
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = new User([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt('123456789'),
                    'verification_code' => rand(100000, 999999)
                ]);
                $user->save();
            }
            $token = $user->createToken('Personal Access Token');
            return $this->loginSuccess($token, $user);
        } else {
            return response()->json(['result' => false, 'message' => translate('Invalid access token.'), 'user' => null], 401);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'result' => true,
            'message' => translate('Successfully logged out')
        ]);
    }

    public function socialLogin(Request $request)
    {
        if (User::where('email', $request->email)->first() != null) {
            $user = User::where('email', $request->email)->first();
        } else {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'provider_id' => $request->provider,
                'email_verified_at' => Carbon::now()
            ]);
            $user->save();
            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        return response()->json([
            'result' => true,
            'message' => translate('Successfully logged in'),
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => api_asset($user->avatar_original),
                'phone' => $user->phone
            ]
        ]);
    }
}
