<?php


namespace App\Http\Controllers\Api\V2;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use App\Models\Cart;

class CheckoutController
{
    public function apply_coupon_code(Request $request){
        $cart_items = Cart::where('user_id', $request->user_id)->get();
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if ($cart_items->isEmpty()) {
            return response()->json([
                'result' => false,
                'message' => translate('Cart is empty')
            ]);
        }

        if ($coupon == null) {
            return response()->json([
                'result' => false,
                'message' => translate('Invalid coupon code!')
            ]);
        }

        $in_range = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;

        if (!$in_range) {
            return response()->json([
                'result' => false,
                'message' => translate('Coupon expired!')
            ]);
        }

        $is_used = CouponUsage::where('user_id', $request->user_id)->where('coupon_id', $coupon->id)->first() != null;

        if ($is_used) {
            return response()->json([
                'result' => false,
                'message' => translate('You already used this coupon!')
            ]);
        }


        $coupon_details = json_decode($coupon->details);

        if ($coupon->type == 'cart_base') {
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            foreach ($cart_items as $key => $cartItem) {
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $shipping += $cartItem['shipping'] * $cartItem['quantity'];
            }
            $sum = $subtotal + $tax + $shipping;

            if ($sum >= $coupon_details->min_buy) {
                if ($coupon->discount_type == 'percent') {
                    $coupon_discount = ($sum * $coupon->discount) / 100;
                    if ($coupon_discount > $coupon_details->max_discount) {
                        $coupon_discount = $coupon_details->max_discount;
                    }
                } elseif ($coupon->discount_type == 'amount') {
                    $coupon_discount = $coupon->discount;
                }

                Cart::where('user_id', $request->user_id)->update([
                    'discount' => $coupon_discount / count($cart_items),
                    'coupon_code' => $request->coupon_code,
                    'coupon_applied' => 1
                ]);

                return response()->json([
                    'result' => true,
                    'discount' => (double) $coupon_discount,
                    'message' => translate('Coupon Applied')
                ]);


            }
        } elseif ($coupon->type == 'product_base') {
            $coupon_discount = 0;
            foreach ($cart_items as $key => $cartItem) {
                foreach ($coupon_details as $key => $coupon_detail) {
                    if ($coupon_detail->product_id == $cartItem['product_id']) {
                        if ($coupon->discount_type == 'percent') {
                            $coupon_discount += $cartItem['price'] * $coupon->discount / 100;
                        } elseif ($coupon->discount_type == 'amount') {
                            $coupon_discount += $coupon->discount;
                        }
                    }
                }
            }


            Cart::where('user_id', $request->user_id)->update([
                'discount' => $coupon_discount / count($cart_items),
                'coupon_code' => $request->coupon_code,
                'coupon_applied' => 1
            ]);

            return response()->json([
                'result' => true,
                'discount' => (double) $coupon_discount,
                'message' => translate('Coupon Applied')
            ]);

        }


    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', $request->user_id)->update([
            'discount' => 0.00,
            'coupon_code' => "",
            'coupon_applied' => 0
        ]);

        return response()->json([
            'result' => true,
            'message' => translate('Coupon Removed')
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = Cart::where('user_id',$request->user_id);
        if(count($cart->get()) > 0){
            $address = Address::updateOrCreate([
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'name' => $request->name,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
            ],
            [
                'user_id' => $request->user_id,
                'name' => $request->name,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
            ]);

            if($request->address_same_type != 0){
                $cart = $cart->update([
                    'temp_user_id' => null,
                    'user_id' => $request->user_id,
                    'address_id' => $address->id
                ]);
            }else{
                $cart = $cart->update([
                    'temp_user_id' => null,
                    'user_id' => $request->user_id,
                ]);
            }
            if($request->payment_method == 1){
                $order = new OrderController;
                $responceOrder = $order->store($request);

                if($responceOrder->getData()->result == 'true') {
                    $response = [
                        'status' => true,
                        'message' => 'Order Successfully Order',
                        'login_type' => $request->login_type
                    ];
                    return response()->json($response,200);
                } else {
                    $response = [
                        'status' => false,
                        'message' => $responceOrder->getData()->message
                    ];
                    return response()->json($response,200);
                }
            }

        }else{
            $response = [
                'status' => false,
                'message' => 'Cart Is Empty',
                'login_type' => $request->login_type
            ];
            return response()->json($response,200);
        }
            
        
    }

    // public function checkout(Request $request)
    // {
    //     if($request->user_id == null){
    //         try{
    //             $request->email_or_phone = $request->email;
    //             $request->name = $request->first_name;
    //             $authController = new AuthController;
    //             $signup = $authController->signup($request);
    //             $result = get_object_vars($signup);
    //             if($result['original']['result'] == false){
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => $result['original']['message']
    //                 ],200);
    //             }else{
    //                 $id = $result['original']['user']['id'];
    //                 $address = Address::updateOrCreate([
    //                         'user_id' => $id,
    //                         'phone' => $request->phone,
    //                         'name' => $request->first_name,
    //                         'address' => $request->address_1,
    //                         'postal_code' => $request->postal_code,
    //                         'country_id' => $request->country_id,
    //                         'state_id' => $request->state_id,
    //                         'city_id' => $request->city_id,
    //                         'set_default' => 0,
    //                     ],
    //                     [
    //                         'user_id' => $id,
    //                         'name' => $request->first_name,
    //                         'address' => $request->address_1,
    //                         'country_id' => $request->country_id,
    //                         'state_id' => $request->state_id,
    //                         'city_id' => $request->city_id,
    //                         'longitude' => $request->longitude,
    //                         'latitude' => $request->latitude,
    //                         'postal_code' => $request->postal_code,
    //                         'phone' => $request->phone,
    //                         'set_default' => 1,
    //                     ]
    //                 );
    //                 $login = $authController->login($request);
    //                 $loginArray = get_object_vars($login);
    //                 if($loginArray['original']['result'] == true){
    //                     $user_id = $loginArray['original']['user']['id'];
    //                     $cart = Cart::where('temp_user_id',$request->tempuserid);
    //                     if($request->address_same_type != 0){
    //                         $cart = $cart->update([
    //                             'temp_user_id' => null,
    //                             'user_id' => $user_id,
    //                             'address_id' => $address->id
    //                         ]);
    //                     }else{
    //                         $cart = $cart->update([
    //                             'temp_user_id' => null,
    //                             'user_id' => $user_id,
    //                         ]);
    //                     }
    //                     if($request->payment_method == 1){
    //                         $request->user_id = $user_id;
    //                         $order = new OrderController;
    //                         $order->store($request);
    //                     }
    //                     $loginArray['original']['redirect'] = $request->login_type;
    //                     return response()->json($loginArray['original'],200);
    //                 }else{
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'login fail please try again!'
    //                     ],200);
    //                 }
    //             }
    //         }catch(\Throwable $th){
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => $th->getMessage()
    //             ],200);
    //         }
    //     }else{
    //         // try{
    //             $cart = Cart::where('user_id',$request->user_id);
    //             if(count($cart->get()) > 0){
    //                 $address = Address::updateOrCreate([
    //                     'user_id' => $request->user_id,
    //                     'phone' => $request->phone,
    //                     'name' => $request->first_name,
    //                     'address' => $request->address_1,
    //                     'postal_code' => $request->postal_code,
    //                     'country_id' => $request->country_id,
    //                     'state_id' => $request->state_id,
    //                     'city_id' => $request->city_id,
    //                     'set_default' => 0,
    //                 ],
    //                 [
    //                     'user_id' => $request->user_id,
    //                     'name' => $request->first_name,
    //                     'address' => $request->address_1,
    //                     'country_id' => $request->country_id,
    //                     'state_id' => $request->state_id,
    //                     'city_id' => $request->city_id,
    //                     'longitude' => $request->longitude,
    //                     'latitude' => $request->latitude,
    //                     'postal_code' => $request->postal_code,
    //                     'phone' => $request->phone,
    //                     'set_default' => 1,
    //                 ]);
    //                 if($request->address_same_type != 0){
    //                     $cart = $cart->update([
    //                         'temp_user_id' => null,
    //                         'user_id' => $request->user_id,
    //                         'address_id' => $address->id
    //                     ]);
    //                 }else{
    //                     $cart = $cart->update([
    //                         'temp_user_id' => null,
    //                         'user_id' => $request->user_id,
    //                     ]);
    //                 }
    //                 if($request->payment_method == 1){
    //                     $order = new OrderController;
    //                     $order->store($request);
    //                 }
    //                 $response = [
    //                     'status' => true,
    //                     'message' => 'Order Successfully Order',
    //                     'login_type' => $request->login_type
    //                 ];
    //                 return response()->json($response,200);
    //             }else{
    //                 $response = [
    //                     'status' => false,
    //                     'message' => 'Cart Is Empty',
    //                     'login_type' => $request->login_type
    //                 ];
    //                 return response()->json($response,200);
    //             }
    //         // }catch(\Throwable $th){
    //         //     return response()->json($th->getMessage(),200);
    //         // }
    //     }
    // }
}
