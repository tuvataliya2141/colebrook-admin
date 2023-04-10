<?php


Route::get('v2/auth/login/google', 'Api\V2\AuthController@googleLogin')->middleware('web');
Route::get('v2/auth/login/google', 'Api\V2\AuthController@googleLogin')->middleware('web');

Route::group(['prefix' => 'v2/auth', 'middleware' => ['app_language']], function() {
    Route::post('login', 'Api\V2\AuthController@login');
    Route::post('signup', 'Api\V2\AuthController@signup');
    Route::post('social-login', 'Api\V2\AuthController@socialLogin');
    Route::post('password/forget_request', 'Api\V2\PasswordResetController@forgetRequest');
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', 'Api\V2\AuthController@logout');
        Route::get('user', 'Api\V2\AuthController@user');
    });
    Route::post('confirm_code', 'Api\V2\AuthController@confirmCode');
});
Route::group(['prefix' => 'v2', 'middleware' => ['app_language']], function() {
    Route::get('products/home', 'Api\V2\ProductController@home');
    Route::post('products/details', 'Api\V2\ProductController@GetProductDetail'); // yes
    Route::post('contact', 'Api\V2\ContactController@store'); // yes
    Route::post('subscribe', 'Api\V2\ContactController@subscribe');
    Route::get('carts/list', 'Api\V2\CartController@getList');
    Route::post('cart/add', 'Api\V2\CartController@add');
    Route::post('mainAllSearch', 'Api\V2\ProductController@mainAllSearch');
    Route::post('products/shopFilter', 'Api\V2\ProductController@shopFilter');
   
    Route::post('cart/update', 'Api\V2\CartController@process');
    // Route::post('products/{category}', 'Api\V2\ProductController@popularProduct'); // yes

    Route::get('wishlists/{id}', 'Api\V2\WishlistController@index');
    Route::apiResource('wishlists', 'Api\V2\WishlistController')->except(['index', 'update', 'show']);
    Route::post('wishlists-add-product', 'Api\V2\WishlistController@add');
    Route::get('wishlists-remove-product', 'Api\V2\WishlistController@remove');

    Route::get('payment-types', 'Api\V2\PaymentTypesController@getList');
    Route::get('products/best-seller', 'Api\V2\ProductController@bestSeller');

    // dharmesh api

    Route::get('allCategory', 'Api\V2\CategoryController@allCategory');
    Route::get('homePage', 'Api\V2\CategoryController@homePage');
    Route::apiResource('banners', 'Api\V2\BannerController')->only('index');
    Route::get('homeCard', 'Api\V2\BannerController@homeCard');
    Route::apiResource('brands', 'Api\V2\BrandController')->only('index');
    Route::get('get-search-suggestions', 'Api\V2\SearchSuggestionController@getList');
    Route::get('products/search', 'Api\V2\ProductController@search');
    Route::apiResource('categories', 'Api\V2\CategoryController')->only('index');
    Route::post('productFliter', 'Api\V2\ProductController@productFliter');
    Route::post('allCartDelete', 'Api\V2\CartController@allCartDelete');
    Route::get('cart/delete/{id}', 'Api\V2\CartController@destroy'); // yes
    Route::post('coupon/apply', 'Api\V2\CouponController@apply');
    Route::get('reviews/product/{id}', 'Api\V2\ReviewController@index')->name('api.reviews.index');
    Route::post('reviews/submit', 'Api\V2\ReviewController@submit')->name('api.reviews.submit');

}); 

Route::group(['prefix' => 'v2', 'middleware' => ['app_language','auth:api']], function() {
    Route::get('languages', 'Api\V2\LanguageController@getList');

    Route::get('brands/top', 'Api\V2\BrandController@top');
    Route::post('order/userOrderList', 'Api\V2\OrderController@userOrderList')->middleware('auth:api');
    Route::get('order/userOrderDetail/{id}', 'Api\V2\OrderController@userOrderDetail')->middleware('auth:api');

    Route::get('categories/featured', 'Api\V2\CategoryController@featured');
    Route::get('categories/home', 'Api\V2\CategoryController@home');
    Route::get('categories/top', 'Api\V2\CategoryController@top');
    
    Route::get('sub-categories/{id}', 'Api\V2\SubCategoryController@index')->name('subCategories.index');

    Route::apiResource('colors', 'Api\V2\ColorController')->only('index');

    Route::apiResource('currencies', 'Api\V2\CurrencyController')->only('index');

    Route::get('filter/categories', 'Api\V2\FilterController@categories');
    Route::get('filter/brands', 'Api\V2\FilterController@brands');

    Route::get('products/category/{id}', 'Api\V2\ProductController@category')->name('api.products.category');
    Route::get('products/sub-category/{id}', 'Api\V2\ProductController@subCategory')->name('products.subCategory');
    Route::get('products/sub-sub-category/{id}', 'Api\V2\ProductController@subSubCategory')->name('products.subSubCategory');
    Route::get('products/brand/{id}', 'Api\V2\ProductController@brand')->name('api.products.brand');
    Route::get('products/todays-deal', 'Api\V2\ProductController@todaysDeal');
    Route::get('products/featured', 'Api\V2\ProductController@featured');
    
    Route::get('products/related/{id}', 'Api\V2\ProductController@related')->name('products.related');

    Route::get('products/variant/price', 'Api\V2\ProductController@variantPrice');
    
    Route::apiResource('products', 'Api\V2\ProductController')->except(['store', 'update', 'destroy']);

    

    Route::get('cart-summary/{user_id}', 'Api\V2\CartController@summary')->middleware('auth:api');
    
    
    Route::post('carts/change-quantity', 'Api\V2\CartController@changeQuantity')->middleware('auth:api');
    // Route::apiResource('carts', 'Api\V2\CartController')->only('destroy')->middleware('auth:api');
    
    


    Route::post('coupon-apply', 'Api\V2\CheckoutController@apply_coupon_code')->middleware('auth:api');
    Route::post('coupon-remove', 'Api\V2\CheckoutController@remove_coupon_code')->middleware('auth:api');

    Route::post('update-address-in-cart', 'Api\V2\AddressController@updateAddressInCart')->middleware('auth:api');

    

    
    

    Route::apiResource('sliders', 'Api\V2\SliderController')->only('index');

    Route::get('wishlists-check-product', 'Api\V2\WishlistController@isProductInWishlist');
    
    Route::post('checkout', 'Api\V2\CheckoutController@checkout');

    Route::get('policies/support', 'Api\V2\PolicyController@supportPolicy')->name('policies.support');
    Route::get('policies/return', 'Api\V2\PolicyController@returnPolicy')->name('policies.return');

    Route::get('user/info/{id}', 'Api\V2\UserController@info')->middleware('auth:api');

    Route::get('user/addresses/{id}', 'Api\V2\UserController@getAddresses')->middleware('auth:api');
    Route::post('user/addAddresses', 'Api\V2\UserController@addAddresses')->middleware('auth:api');
    Route::get('user/updateAddresses/{addressId}', 'Api\V2\UserController@updateAddresses')->middleware('auth:api');
    Route::post('user/deleteAddresses', 'Api\V2\UserController@deleteAddresses')->middleware('auth:api');
    
    Route::post('user/info/update', 'Api\V2\UserController@updateName')->middleware('auth:api');
    Route::get('user/shipping/address/{id}', 'Api\V2\AddressController@addresses')->middleware('auth:api');
    Route::post('user/shipping/create', 'Api\V2\AddressController@createShippingAddress')->middleware('auth:api');
    Route::post('user/shipping/update', 'Api\V2\AddressController@updateShippingAddress')->middleware('auth:api');
    Route::post('user/shipping/update-location', 'Api\V2\AddressController@updateShippingAddressLocation')->middleware('auth:api');
    Route::post('user/shipping/make_default', 'Api\V2\AddressController@makeShippingAddressDefault')->middleware('auth:api');
    Route::get('user/shipping/delete/{id}', 'Api\V2\AddressController@deleteShippingAddress')->middleware('auth:api');

    Route::get('refund-request/get-list/{id}', 'Api\V2\RefundRequestController@get_list')->middleware('auth:api');
    Route::post('refund-request/send', 'Api\V2\RefundRequestController@send')->middleware('auth:api');

    Route::post('get-user-by-access_token', 'Api\V2\UserController@getUserInfoByAccessToken');

    Route::get('cities', 'Api\V2\AddressController@getCities');
    Route::get('states', 'Api\V2\AddressController@getStates');
    Route::get('countries', 'Api\V2\AddressController@getCountries');

    Route::get('cities-by-state/{state_id}', 'Api\V2\AddressController@getCitiesByState');
    Route::get('states-by-country/{country_id}', 'Api\V2\AddressController@getStatesByCountry');

    Route::post('shipping_cost', 'Api\V2\ShippingController@shipping_cost')->middleware('auth:api');

    


    Route::any('stripe', 'Api\V2\StripeController@stripe');
    Route::any('/stripe/create-checkout-session', 'Api\V2\StripeController@create_checkout_session')->name('api.stripe.get_token');
    Route::any('/stripe/payment/callback', 'Api\V2\StripeController@callback')->name('api.stripe.callback');
    Route::any('/stripe/success', 'Api\V2\StripeController@success')->name('api.stripe.success');
    Route::any('/stripe/cancel', 'Api\V2\StripeController@cancel')->name('api.stripe.cancel');

    Route::any('paypal/payment/url', 'Api\V2\PaypalController@getUrl')->name('api.paypal.url');
    Route::any('paypal/payment/done', 'Api\V2\PaypalController@getDone')->name('api.paypal.done');
    Route::any('paypal/payment/cancel', 'Api\V2\PaypalController@getCancel')->name('api.paypal.cancel');

    Route::any('razorpay/pay-with-razorpay', 'Api\V2\RazorpayController@payWithRazorpay')->name('api.razorpay.payment');
    Route::any('razorpay/payment', 'Api\V2\RazorpayController@payment')->name('api.razorpay.payment');
    Route::post('razorpay/success', 'Api\V2\RazorpayController@success')->name('api.razorpay.success');

    Route::post('payments/pay/cod', 'Api\V2\PaymentController@cashOnDelivery')->middleware('auth:api');
    Route::post('payments/pay/manual', 'Api\V2\PaymentController@manualPayment')->middleware('auth:api');

    Route::post('offline/payment/submit', 'Api\V2\OfflinePaymentController@submit')->name('api.offline.payment.submit');

    Route::post('order/store', 'Api\V2\OrderController@store')->middleware('auth:api');
    Route::get('profile/counters/{user_id}', 'Api\V2\ProfileController@counters')->middleware('auth:api');
    Route::post('profile/update', 'Api\V2\ProfileController@update')->middleware('auth:api');
    Route::post('profile/update-device-token', 'Api\V2\ProfileController@update_device_token')->middleware('auth:api');
    Route::post('profile/update-image', 'Api\V2\ProfileController@updateImage')->middleware('auth:api');
    Route::post('profile/check-phone-and-email', 'Api\V2\ProfileController@checkIfPhoneAndEmailAvailable')->middleware('auth:api');

    // help and support routes
    Route::post('ticket-support', 'Api\V2\SupportTicketController@tikect_support');


    

    
});

Route::fallback(function() {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
