<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\BusinessSetting;
use App\Models\User;
use DB;
use App\Models\CombinedOrder;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    public function store(Request $request) {
        // dd($request->all());
        $cartItems = Cart::where('user_id', $request->user_id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'combined_order_id' => 0,
                'result' => false,
                'message' => translate('Cart is Empty')
            ]);
        }

        $address = Address::where('id', $cartItems->first()->address_id)->first();
        
        $user = User::find($request->user_id);

        $shippingAddress = [];

        if((isset($request->address_same_type)) && ($request->address_same_type == 0 || $request->address_same_type == null)){
            if ($address != null) {
                $shippingAddress['name']        = $user->name;
                $shippingAddress['email']       = $user->email;
                $shippingAddress['address']     = $address->address;
                $shippingAddress['country']     = $address->country->name;
                $shippingAddress['state']       = $address->state->name;
                $shippingAddress['city']        = $address->city->name;
                $shippingAddress['postal_code'] = $address->postal_code;
                $shippingAddress['phone']       = $address->phone;
                if ($address->latitude || $address->longitude) {
                    $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
                }
            }
        }else{
            $country = Country::where('id',$request->country_id)->first();
            $country_name = $country->name;
            $state = State::where('id',$request->state_id)->first();
            $state_name = $state->name;
            $city = City::where('id',$request->city_id)->first();
            $city_name = $city->name;
            $shippingAddress['name']        = $request->name;
            $shippingAddress['email']       = $request->email;
            $shippingAddress['address']     = $request->address;
            $shippingAddress['country']     = $country_name;
            $shippingAddress['state']       = $state_name;
            $shippingAddress['city']        = $city_name;
            $shippingAddress['postal_code'] = $request->postal_code;
            $shippingAddress['phone']       = $request->phone;
            if ($request->latitude || $request->longitude) {
                $shippingAddress['lat_lang'] = $request->latitude . ',' . $request->longitude;
            }
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();


        $seller_products = array();
        foreach ($cartItems as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        foreach ($seller_products as $seller_product) {
            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            $order->user_id = $user->id;
            $order->shipping_address = json_encode($shippingAddress);            

            $order->payment_type = $request->payment_type;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            if($request->payment_id != null) {
                $order->payment_status = 'paid';
            } else {                
                $order->payment_status = $request->payment_status;
            }
            if($request->sender_name){
                $order->sender_name = $request->sender_name;
            }
            if($request->sender_message){
                $order->sender_message = $request->sender_message;
            }
            if($request->sender_adrs){
                 $order->sender_adrs = $request->sender_adrs;
            }
            if($request->delivery_type){
               $order->delivery_type = $request->delivery_type;
            }
             if($request->delivery_date){
               $order->delivery_date = $request->delivery_date;
            }
            if($request->delivery_timeslot){
               $order->delivery_timeslot = $request->delivery_timeslot;
            }
            if($request->payment_id){
               $order->payment_details = $request->payment_id;
            }
               
          
            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;

            //Order Details Storing
            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);

                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

                $product_variation = $cartItem['variation'];

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
                $order_detail->shipping_cost = $cartItem['shipping_cost'];

                $shipping += $order_detail->shipping_cost;

                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order_detail->pickup_point_id = $cartItem['pickup_point'];
                }
                //End of storing shipping cost

                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale = $product->num_of_sale + $cartItem['quantity'];
                $product->save();

                $order->seller_id = $product->user_id;
            }

            $order->grand_total = $subtotal + $tax + $shipping;

            if ($seller_product[0]->coupon_code != null) {
                // if (Session::has('club_point')) {
                //     $order->club_point = Session::get('club_point');
                // }
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = $user->id;
                $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }

            $combined_order->grand_total += $order->grand_total;

            if (strpos($request->payment_type, "manual_payment_") !== false) { // if payment type like  manual_payment_1 or  manual_payment_25 etc)
                $order->manual_payment = 1;
                $order->save();
            }

            $order->save();
        }

        
        if($request->payment_type == 'Razorpay') {
            $api_key = env('RAZORPAY_KEY_ID');
            $api_secret = env('RAZORPAY_KEY_SECRET');
            $orderAmount = $order->grand_total * 100;

            $apiRazorpay = new Api($api_key, $api_secret);
            $apiRazorpay->payment->fetch($request->payment_id)->capture(array('amount'=>$orderAmount,'currency' => 'INR'));
        }        

        $combined_order->save();

        $orderjsonData = $this->addJsonOrder($order, $order_detail, $seller_product);
        
        if(json_decode($orderjsonData)->status == "success"){
            $responseData = json_decode($orderjsonData)->data;
            $waybill = '';
            foreach($responseData as $response) {
                $waybill = $response->waybill;
            }
            $order->waybill = $waybill;
            $order->save();

            Cart::where('user_id', $request->user_id)->delete();

            return response()->json([
                'combined_order_id' => $combined_order->id,
                'result' => true,
                'message' => translate('Your order has been placed successfully')
            ]);
        } else {
            OrderDetail::where('order_id', $order->id)->delete();
            Order::where('id', $order->id)->delete();
            $orderResponse = (array)json_decode($orderjsonData)->data;
            $orderResponse = (array)$orderResponse[1];

            return response()->json([
                'result' => false,
                'message' => translate($orderResponse['remark'])
            ]);
        }        
    }

    public function addJsonOrder($order, $order_detail, $seller_product)
    {
        $proData = [];
        $totalWeight = 0;
        $totallength = 0;
        $totalwidth = 0;
        $totalheight = 0;
        $totalDiscount = 0;
        foreach ($seller_product as $key => $value) {
            $productData = Product::where('id', $value->product_id)->first();
            $totalWeight+= $productData->weight;
            $totallength+= $productData->shipment_length;
            $totalwidth+= $productData->shipment_width;
            $totalheight+= $productData->shipment_height;
            $totalDiscount+= $productData->discount;
            $pro_data = [
                'product_name' => $productData->name,
                'product_sku' => $productData->sku,
                'product_quantity' => $productData->min_qty,
                'product_price' => home_discounted_base_price($productData, false),
                'product_tax_rate' => $productData->tax,
                'product_hsn_code' => $productData->hsn_code,
                'product_discount' => discount_in_percentage($productData),
            ];
            $proData[] = $pro_data;
        }
        if($order->payment_type == 'cash_on_delivery'){
            $payment_type = 'cod';
            $codmount = $order->grand_total;
        } else {
            $payment_type = 'Prepaid';
            $codmount = 0;
        }
        $order_date = date("d-m-Y",$order->date);
        $jsonData = '{
            "data":{
                "shipments" : [
                {
                    "waybill" : "",
                    "order" : "'. $order->code .'",
                    "sub_order" : "",
                    "order_date" : "'. $order_date .'",
                    "total_amount" : "'. $order->grand_total .'",
                    "name" : "'. json_decode($order->shipping_address)->name .'",
                    "company_name" : "",
                    "add" : "'. json_decode($order->shipping_address)->address .'",
                    "add2" : "",
                    "add3" : "",
                    "pin" : "'. json_decode($order->shipping_address)->postal_code .'",
                    "city" : "'. json_decode($order->shipping_address)->city .'",
                    "state" : "'. json_decode($order->shipping_address)->state .'",
                    "country" : "'. json_decode($order->shipping_address)->country .'",
                    "phone" : "'. json_decode($order->shipping_address)->phone .'",
                    "alt_phone" : "",
                    "email" : "'. json_decode($order->shipping_address)->email .'",
                    "is_billing_same_as_shipping" : "no",
                    "billing_name" : "'. json_decode($order->shipping_address)->name .'",
                    "billing_company_name" : "",
                    "billing_add" : "'. json_decode($order->shipping_address)->address .'",
                    "billing_add2" : "",
                    "billing_add3" : "",
                    "billing_pin" : "'. json_decode($order->shipping_address)->postal_code .'",
                    "billing_city" : "'. json_decode($order->shipping_address)->city .'",
                    "billing_state" : "'. json_decode($order->shipping_address)->state .'",
                    "billing_country" : "'. json_decode($order->shipping_address)->country .'",
                    "billing_phone" : "'. json_decode($order->shipping_address)->phone .'",
                    "billing_alt_phone" : "",
                    "billing_email" : "'. json_decode($order->shipping_address)->email .'",
                    "products" : ' . json_encode($proData) . ',
                    "shipment_length" : "'. $totallength .'",
                    "shipment_width" : "'. $totalwidth .'",
                    "shipment_height" : "'. $totalheight .'",
                    "weight" : "'. $totalWeight .'",
                    "shipping_charges" : "0",
                    "giftwrap_charges" : "0",
                    "transaction_charges" : "0",
                    "total_discount" : "0",
                    "first_attemp_discount" : "0",
                    "cod_charges" : "0",
                    "advance_amount" : "0",
                    "cod_amount" : "'.$codmount.'",
                    "payment_mode" : "'. $payment_type .'",
                    "reseller_name" : "",
                    "eway_bill_number" : "",
                    "gst_number" : "",
                    "return_address_id" : "1293"
                }],
                "pickup_address_id" : "1293",
                "access_token" : "5a7b40197cd919337501dd6e9a3aad9a",
                "secret_key" : "2b54c373427be180d1899400eeb21aab",
                "logistics" : "Delhivery",
                "s_type" : "",
                "order_type" : ""
            }
        }';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => "https://pre-alpha.ithinklogistics.com/api_v3/order/add.json",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $jsonData,
            CURLOPT_HTTPHEADER      => array(
                "cache-control: no-cache",
                "content-type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) 
        {
            return "cURL Error #:" . $err;
        }
        else
        {
            return $response;
        }
    }

    public function userOrderList(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $user_id = $request->user_id;
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            //->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('user_id', $user_id)
            ->select('orders.*')
            ->distinct();

        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);
        // dd($orders);
        foreach ($orders as $key => $value) {
            $order = \App\Models\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }
        
        if($orders){
            return response()->json([
                'status' => true,
                'message' => 'List fatch successfully',
                'data' => $orders
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ],200);
        }
    }
    
    public function userOrderDetail($id)
    {
        
        $order = Order::findOrFail($id);
        if($order){
            $response = [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'shipping_address' => json_decode($order->shipping_address),
                'delivery_status' => $order->delivery_status,
                'payment_type' => $order->payment_type,
                'payment_status' => $order->payment_status,
                'payment_details' => $order->payment_details,
                'grand_total' => $order->grand_total,
                'coupon_discount' => $order->coupon_discount,
                'code' => $order->code,
                'tracking_code' => $order->tracking_code,
                'date' => date("d-m-Y",$order->tracking_code),
                // 'delivery_history_date' => date("d-m-Y",$order->delivery_history_date),
            ];
            
            if($response){
                return response()->json([
                    'status' => true,
                    'message' => 'Data fatch successfully',
                    'data' => $response
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found',
                    'data' => []
                ],200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ],200);
        }
    }


    public function userOrderSummary($id) {
        $orderSummary = OrderDetail::select('order_details.*', 'products.thumbnail_img', 'products.name')
                        ->where('order_details.order_id', $id)
                        ->join('products', 'order_details.product_id', '=', 'products.id')
                        ->get();

        foreach ($orderSummary as $order) {
            $order->thumbnailImage = api_asset($order->thumbnail_img);
        }
        
        if($orderSummary){
            return response()->json([
                'status' => true,
                'message' => 'List fatch successfully',
                'data' => $orderSummary
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ],200);
        }
    }

    public function trackOrder(Request $request)
    {
        $order = Order::where('user_id',$request->user_id)->where('code', $request->order_code)->first();
        if ($order == Null) {
            return response()->json([
                'result' => false,
                'message' => translate('Order code incorrect'),
                'data' => []
            ]);
        }
        $response = '';
        if($order){
            $date = strtotime("+7 day", $order->date);
            $response = [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'delivery_status' => $order->delivery_status,
                'code' => $order->code,
                'date' => date("d-m-Y",$order->date),
                'delivery_date' => date("d-m-Y",$date),
            ];
        }
        return response()->json([
            'status' => true,
            'message' => 'Data fatch successfully',
            'data' => $response
        ],200);

        // $awb_order = '1333110027760';
        // $awb_order = $order->waybill;
        // if($awb_order != null){
        //     $jsonData = ' {
        //         "data":{
        //             "awb_number_list"    : "'. $awb_order .'",      #List of AWB Number which you want to track.
        //             "access_token" : "8ujik47cea32ed386b1f65c85fd9aaaf",
        //             "secret_key" : "65tghjmads9dbcd892ad4987jmn602a7"
        //             }
        //         }';
        //     // dd(json_encode($jsonData));    
        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL             => "https://pre-alpha.ithinklogistics.com/api_v3/order/track.json",
        //         CURLOPT_RETURNTRANSFER  => true,
        //         CURLOPT_ENCODING        => "",
        //         CURLOPT_MAXREDIRS       => 10,
        //         CURLOPT_TIMEOUT         => 30,
        //         CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST   => "POST",
        //         CURLOPT_POSTFIELDS      => json_encode($jsonData),
        //         CURLOPT_HTTPHEADER      => array(
        //             "cache-control: no-cache",
        //             "content-type: application/json"
        //         )
        //     ));

        //     $response = curl_exec($curl);
        //     $err      = curl_error($curl);
        //     curl_close($curl);
        //     if ($err) 
        //     {
        //         return response()->json([
        //             'result' => false,
        //             'message' => "cURL Error #:" . $err
        //         ]);
        //     }
        //     else
        //     {
        //         return response()->json([
        //             'status' => true,
        //             'message' => 'Data fatch successfully',
        //             'data' => json_decode($response)
        //         ],200);
        //     }
        // } else {
        //     return response()->json([
        //         'result' => false,
        //         'message' => translate('Order code incorrect')
        //     ]);
        // }   
    }


    public function check_pincode(Request $request)
    {
        $pincode = $request->pincode;
        // dd($pincode);
        if ($pincode) {

            $jsonData = '  {
                "data":{
                    "pincode"  : "'. $pincode .'",      #pincode whose data is needed.
                    "access_token" : "8ujik47cea32ed386b1f65c85fd9aaaf",
                    "secret_key" : "65tghjmads9dbcd892ad4987jmn602a7"
                }
            }';
            // dd(json_encode($jsonData));    
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL             => "https://pre-alpha.ithinklogistics.com/api_v3/pincode/check.json",
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => "",
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POSTFIELDS      => json_encode($jsonData),
                CURLOPT_HTTPHEADER      => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                )
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);
            curl_close($curl);
            if ($err) 
            {
                return response()->json([
                    'result' => false,
                    'message' => "cURL Error #:" . $err
                ]);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Data fatch successfully',
                    'data' => json_decode($response)
                ],200);
            }
            
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('Pin code incorrect')
            ]);
        } 
    }

    public function order_cancel(Request $request)
    {
        $awb_numbers = $request->awb_numbers;
        // dd($pincode);
        if ($awb_numbers) {

            $jsonData = '  {
                "data":{
                    "awb_numbers" : "'. $awb_numbers .'",
                    "access_token" : "5a7b40197cd919337501dd6e9a3aad9a",
                    "secret_key" : "2b54c373427be180d1899400eeb21aab"
                }
            }';
            // dd(json_encode($jsonData));    
            $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL             => "https://pre-alpha.ithinklogistics.com/api_v3/order/cancel.json",
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => "",
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POSTFIELDS      => $jsonData,
                CURLOPT_HTTPHEADER      => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                )
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);
            curl_close($curl);
            if ($err) 
            {
                return response()->json([
                    'result' => false,
                    'message' => "cURL Error #:" . $err
                ]);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Data fatch successfully',
                    'data' => json_decode($response)
                ],200);
            }
            
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('AWD numbers incorrect')
            ]);
        } 
    }
}
