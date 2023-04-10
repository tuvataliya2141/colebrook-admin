<?php


namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;

class PaymentTypesController
{

    public function getList(Request $request)
    {
        $mode = "order";

        if ($request->has('mode')) {
            $mode = $request->mode; // wallet or other things , comes from query param ?mode=wallet
        }

        $list = "both";
        if ($request->has('list')) {
            $list = $request->list; // ?list=offline
        }

        $payment_types = array();

        if ($list == "online" || $list == "both") {
            if (get_setting('paypal_payment') == 1) {
                $payment_type = array();
                $payment_type['payment_type'] = 'paypal_payment';
                $payment_type['payment_type_key'] = 'paypal';
                $payment_type['image'] = static_asset('assets/img/cards/paypal.png');
                $payment_type['name'] = "Paypal";
                $payment_type['title'] = "Checkout with Paypal";
                $payment_type['offline_payment_id'] = 0;
                $payment_type['details'] = "";
                if ($mode == 'wallet') {
                    $payment_type['title'] = "Recharge with Paypal";
                }

                $payment_types[] = $payment_type;
            }

            if (get_setting('stripe_payment') == 1) {
                $payment_type = array();
                $payment_type['payment_type'] = 'stripe_payment';
                $payment_type['payment_type_key'] = 'stripe';
                $payment_type['image'] = static_asset('assets/img/cards/stripe.png');
                $payment_type['name'] = "Stripe";
                $payment_type['title'] = "Checkout with Stripe";
                $payment_type['offline_payment_id'] = 0;
                $payment_type['details'] = "";
                if ($mode == 'wallet') {
                    $payment_type['title'] = "Recharge with Stripe";
                }

                $payment_types[] = $payment_type;
            }

            if (get_setting('razorpay') == 1) {
                $payment_type = array();
                $payment_type['payment_type'] = 'razorpay';
                $payment_type['payment_type_key'] = 'razorpay';
                $payment_type['image'] = static_asset('assets/img/cards/rozarpay.png');
                $payment_type['name'] = "Razorpay";
                $payment_type['title'] = "Checkout with Razorpay";
                $payment_type['offline_payment_id'] = 0;
                $payment_type['details'] = "";
                if ($mode == 'wallet') {
                    $payment_type['title'] = "Recharge with Razorpay";
                }

                $payment_types[] = $payment_type;
            }
        }
        return response()->json($payment_types);
    }
}
