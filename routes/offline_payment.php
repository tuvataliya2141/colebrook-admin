<?php

/*
|--------------------------------------------------------------------------
| Offline Payment Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::resource('manual_payment_methods','ManualPaymentMethodController');
    Route::get('/manual_payment_methods/destroy/{id}', 'ManualPaymentMethodController@destroy')->name('manual_payment_methods.destroy');

    // customer package purchase request
    Route::get('/offline-customer-package-payment-requests', 'CustomerPackagePaymentController@offline_payment_request')->name('offline_customer_package_payment_request.index');
    Route::post('/offline-customer-package-payment/approved', 'CustomerPackagePaymentController@offline_payment_approval')->name('offline_customer_package_payment.approved');

});

//FrontEnd
Route::post('/purchase_history/make_payment', 'ManualPaymentMethodController@show_payment_modal')->name('checkout.make_payment');
Route::post('/purchase_history/make_payment/submit', 'ManualPaymentMethodController@submit_offline_payment')->name('purchase_history.make_payment');
Route::post('/offline-wallet-recharge-modal', 'ManualPaymentMethodController@offline_recharge_modal')->name('offline_wallet_recharge_modal');

// customer package purchase
Route::post('/offline-customer-package-purchase-modal', 'ManualPaymentMethodController@offline_customer_package_purchase_modal')->name('offline_customer_package_purchase_modal');
Route::post('/offline-customer-package-paymnet', 'CustomerPackageController@purchase_package_offline')->name('customer_package.make_offline_payment');
