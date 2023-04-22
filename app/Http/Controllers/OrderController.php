<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\ProductStock;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\CombinedOrder;
use PayPal\Api\Amount;
use PayPal\Api\Refund;
use PayPal\Api\Payment;
use PayPal\Exception\PayPalConnectionException;
use Razorpay\Api\Api;
use Stripe\Stripe;
// use Stripe\Refund;
use Auth;
use DB;

class OrderController extends Controller
{
    // All Orders
    public function all_orders(Request $request)
    {
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;

        $orders = Order::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.all_orders.index', compact('orders', 'sort_search', 'delivery_status', 'date'));
    }

    public function all_orders_show($id){
        $order = Order::findOrFail(decrypt($id));
        // dd($order->orderDetails);
        $orderDetail = OrderDetail::where('order_id',decrypt($id))->first();
        $orderCart = Cart::where('product_id',$orderDetail['product_id'])->where('user_id', $order->user_id)->first();
        $order_shipping_address = json_decode($order->shipping_address);
        return view('backend.sales.all_orders.show', compact('order', 'orderCart'));
    }

    // Inhouse Orders
    public function admin_orders(Request $request)
    {
        $date = $request->date;
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $admin_user_id = User::where('user_type', 'admin')->first()->id;
        $orders = Order::orderBy('id', 'desc');

        if ($request->payment_type != null) {
            $orders = $orders->where('payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($date != null) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }

        $orders = $orders->paginate(15);
        return view('backend.sales.inhouse_orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search', 'admin_user_id', 'date'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $order->viewed = 1;
        $order->save();
        return view('backend.sales.inhouse_orders.show', compact('order'));
    }

    // Pickup point orders
    public function pickup_point_order_index(Request $request)
    {
        $date = $request->date;
        $sort_search = null;

        if (Auth::user()->user_type == 'staff' && Auth::user()->staff->pick_up_point != null) {
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.pickup_point_id', Auth::user()->staff->pick_up_point->id)
                ->select('orders.id')
                ->distinct();

            if ($request->has('search')) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($date != null) {
                $orders = $orders->whereDate('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            }

            $orders = $orders->paginate(15);

            return view('backend.sales.pickup_point_orders.index', compact('orders', 'sort_search', 'date'));
        } else {
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.shipping_type', 'pickup_point')
                ->select('orders.id')
                ->distinct();

            if ($request->has('search')) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($date != null) {
                $orders = $orders->whereDate('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            }

            $orders = $orders->paginate(15);

            return view('backend.sales.pickup_point_orders.index', compact('orders', 'sort_search', 'date'));
        }
    }

    public function pickup_point_order_sales_show($id)
    {
        if (Auth::user()->user_type == 'staff') {
            $order = Order::findOrFail(decrypt($id));
            $order_shipping_address = json_decode($order->shipping_address);
            return view('backend.sales.pickup_point_orders.show', compact('order'));
        } else {
            $order = Order::findOrFail(decrypt($id));
            $order_shipping_address = json_decode($order->shipping_address);
            return view('backend.sales.pickup_point_orders.show', compact('order'));
        }
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $address = Address::where('id', $carts[0]['address_id'])->first();
        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = $address->name ; //Auth::user()->name;
            $shippingAddress['email']       = Auth::user()->email;
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

        $combined_order = new CombinedOrder;
        
        $combined_order->user_id = Auth::user()->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        $combined_order->save();

        $request->session()->put('combined_order_id', $combined_order->id);

        return response()->json([
            'result' => true,
            'message' => translate('Order placed successfully')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }

                } catch (\Exception $e) {

                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        foreach ($order->orderDetails as $key => $orderDetail) {

            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();

            if ($request->status == 'cancelled') {
                $variant = $orderDetail->variation;
                if ($orderDetail->variation == null) {
                    $variant = '';
                }

                $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                    ->where('variant', $variant)
                    ->first();

                if ($product_stock != null) {
                    $product_stock->qty += $orderDetail->quantity;
                    $product_stock->save();
                }
            }
        }
        return 1;
    }

   public function update_tracking_code(Request $request) {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
   }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();
        return 1;
    }

    public function cancel_order($orderId)
    {
        $orderID = decrypt($orderId);
        $order = Order::where('id', $orderID)->first();
        // dd($order);
        if($order->payment_type == 'Razorpay'){
            // $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
            
            // $order_id = "pay_LfBZ6dn005RnhY"; // Replace with your order ID

            // $razorpayOrder = $api->order->fetch($order_id);
            // dd($razorpayOrder);
            // if ($razorpayOrder->status === 'created') {
            //     $api->order->cancel($order_id);
            //     $refund = $api->refund->create([
            //         'payment_id' => $razorpayOrder->payments[0]->id,
            //         'amount' => $razorpayOrder->amount_paid,
            //         'notes' => [
            //             'reason' => 'Order cancelled'
            //         ]
            //     ]);
            // }
        }elseif($order->payment_type == 'Stripe'){
            // Stripe::setApiKey(env('STRIPE_SECRET'));

            // $charge_id = 'ch_12345'; // Replace with your charge ID

            // $refund = Refund::create([
            //     'charge' => $charge_id,
            // ]);

            // if ($refund->status == 'succeeded') {
            //     echo 'Refund succeeded';
            // } else {
            //     echo 'Refund failed';
            // }

        }elseif($order->payment_type == 'Paypal'){
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    'AQ--tCY2U0vaYX5l4J5ugzv-bH1COHRa7t6ajPd9YCkyQE0wpbnBGeB9IrjFQd0oZ2kPdUkLCEin0ly_',
                    'EJjBXx65TqBrxJmstrYxT5QBEhbXejo8pA8mGtD_w7fIzwPDdcFoobEcmIZ_V6gSPcGEJptyzvHgGae3'
                )
            );
            // dd($apiContext);
            try {
                // Retrieve the payment object from PayPal
                $payment = Payment::get($order->payment_details, $apiContext);
                
                // Get the payment amount
                $amount = $payment->getTransactions()[0]->getAmount()->getTotal();
                dd($amount);
                // Create a new refund object
                $refund = new Refund();
                $refund->setAmount(new Amount([
                    'total' => $amount,
                    'currency' => 'USD'
                ]));
        
                // Process the refund
                $sale = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale();
                $refundResult = $sale->refund($refund, $apiContext);
        
                // Output the refund status
                echo "Refund status: " . $refundResult->getState();
            } catch (PayPalConnectionException $ex) {
                // Handle any errors
                echo $ex->getMessage();
            }

        }
        dd($order);
        if ($order->waybill) {
            $awb_numbers = $order->waybill;
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
        }
        $order->delivery_viewed = '0';
        $order->delivery_status = 'cancelled';
        $order->save();
        foreach ($order->orderDetails as $key => $orderDetail) {

            $orderDetail->delivery_status = 'cancelled';
            $orderDetail->save();
            $variant = $orderDetail->variation;
            if ($orderDetail->variation == null) {
                $variant = '';
            }

            $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                ->where('variant', $variant)
                ->first();

            if ($product_stock != null) {
                $product_stock->qty += $orderDetail->quantity;
                $product_stock->save();
            }
            
        }
        flash(translate('Order has been cancel successfully'))->success();
        return back();
    }
}
