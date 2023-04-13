<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\UserCollection;
use App\Http\Resources\V2\AddressCollection;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use DB;

class UserController extends Controller
{
    public function info($id)
    {
        return new UserCollection(User::where('id', $id)->get());
    }

    public function getAddresses($id) {
        return new AddressCollection(Address::where('user_id', $id)->get());
    }

    public function addAddresses(Request $request) {
        if ($request->addressId == 0) {
            $address = new Address;
            $address->user_id = $request->userId;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->address = $request->address;
            $address->country_id = $request->Country;
            $address->state_id = $request->state;
            $address->city_id = $request->city;
            $address->postal_code = $request->PostalCode;
            $address->phone = $request->phone;
            $address->save();

            return response()->json([
                'result' => true,
                'message' => translate('Shipping information has been added successfully')
            ]);
        } else {
            $address = Address::findOrFail($request->addressId);
            if ($address) {
                $address->name = $request->name;
                $address->phone = $request->phone;
                $address->address = $request->address;
                $address->country_id = $request->Country;
                $address->state_id = $request->state;
                $address->city_id = $request->city;
                $address->postal_code = $request->PostalCode;
                if($address->update()) {
                    return response()->json([
                        'result' => true,
                        'message' => translate('Address has been updated successfully')
                    ]);
                }
            }
        }        
    }

    public function updateAddresses($addressId) {
        $address = Address::where('id', $addressId)->first();
        
        return response()->json([
            'result' => true,
            'data' => $address,
            'message' => translate('Address has been updated successfully')
        ]);
    }

    public function deleteAddresses(Request $request) {
        $address = Address::findOrFail($request->id);

        if($address) {
            if($address->delete()) {
                return response()->json([
                    'result' => true,
                    'message' => translate('Address has been deleted successfully')
                ]);
            }
        }
    }

    public function updateName(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update([
            'name' => $request->name
        ]);
        return response()->json([
            'message' => translate('Profile information has been updated successfully')
        ]);
    }

    public function getUserInfoByAccessToken(Request $request)
    {
        //$token = $request->bearerToken();
        $token = $request->access_token;

        $false_response = [
            'result' => false,
            'id' => 0,
            'name' => "",
            'email' => "",
            'avatar' => "",
            'avatar_original' => "",
            'phone' => ""
        ];

        if($token == "" || $token == null){
            return response()->json($false_response);
        }

        try {
            $token_id = (new Parser())->parse($token)->getClaims()['jti']->getValue();
        } catch (\Exception $e) {
            return response()->json($false_response);
        }

        $oauth_access_token_data =  DB::table('oauth_access_tokens')->where('id', '=', $token_id)->first();

        if($oauth_access_token_data == null){
            return response()->json($false_response);
        }

        $user = User::where('id', $oauth_access_token_data->user_id)->first();

        if ($user == null) {
            return response()->json($false_response);

        }

        return response()->json([
            'result' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_original' => api_asset($user->avatar_original),
            'phone' => $user->phone
        ]);

    }
}
