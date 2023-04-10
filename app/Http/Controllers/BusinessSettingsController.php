<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\Product;
use App\Models\PinCodeDelivery;
use Artisan;
use DB;

class BusinessSettingsController extends Controller
{
    public function general_setting(Request $request)
    {
    	return view('backend.setup_configurations.general_settings');
    }

    public function activation(Request $request)
    {
    	return view('backend.setup_configurations.activation');
    }
    // Code cange by Brijesh on 24-May-22 CR#2 - start
    public function location(Request $request)
    {
        $country = Country::all();
        $product = Product::all();
        $pinData = DB::table('pin_code_deliveries AS d')
            ->leftJoin('countries AS c', 'd.country_level', '=', 'c.id')
            ->leftJoin('states AS s', 'd.state_level', '=', 's.id')
            ->leftJoin('cities AS t', 'd.city_level', '=', 't.id')
            ->leftJoin('products AS p', 'd.product', '=', 'p.id')
            ->select('d.*', 'c.name AS co_name', 's.name AS state_name', 't.name AS city_name', 'p.name AS pro_name')
            ->get();
        // dd($pinData);
    	return view('backend.setup_configurations.location', compact('country', 'product', 'pinData'));
    }

    public function getState(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)->get(["name","id"]);
        return response()->json($data);
    }
    public function getCity(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)->get(["name","id"]);
        return response()->json($data);
    }

    public function locationEdit($id)
    {
        // dd($id);
        $getData = PinCodeDelivery::where('id', $id)->first();
        $country = Country::all();
        $product = Product::all();
        $states = State::where("country_id",$getData->country_level)->get(["name","id"]);
        $cities = City::where("state_id",$getData->state_level)->get(["name","id"]);
        return view('backend.setup_configurations.location_edit', compact('country', 'product', 'getData', 'states', 'cities'));
    }

    public function locationAdd(Request $request)
    {
        $pin_code = explode(',',$request->pin_code);
        foreach($pin_code as $key => $value){
            foreach($request->product as $key => $pro){
                $pinCodeDelivery = new PinCodeDelivery;
                $pinCodeDelivery->country_level = $request->country_level;
                $pinCodeDelivery->state_level = $request->state_level;
                $pinCodeDelivery->city_level = $request->city_level;
                $pinCodeDelivery->additional_shippingcost = $request->additional_shippingcost;
                $pinCodeDelivery->pin_code = $value;
                $pinCodeDelivery->product = $pro;
                $pinCodeDelivery->save();
            }
        }
        flash(translate('PIN Code wise Delivery has been added successfully'))->success();
        return redirect()->route('location.index');
    }

    public function locationUpdate(Request $request)
    {
        // dd($request->all());
        $pinCodeDelivery = PinCodeDelivery::findOrFail($request->id);
        $pinCodeDelivery->country_level = $request->country_level;
        $pinCodeDelivery->state_level = $request->state_level;
        $pinCodeDelivery->city_level = $request->city_level;
        $pinCodeDelivery->additional_shippingcost = $request->additional_shippingcost;
        $pinCodeDelivery->pin_code = $request->pin_code;
        $pinCodeDelivery->product = $request->product;
        $pinCodeDelivery->save();
        flash(translate('PIN Code wise Delivery has been updated successfully'))->success();   
        return redirect()->route('location.index');
    }

    public function locationDestroy($id)
    {
        PinCodeDelivery::find($id)->delete();
        flash(translate('PIN Code wise Delivery has been deleted successfully'))->success();
        return redirect()->route('location.index');
    }
    // Code cange by Brijesh on 24-May-22 CR#2 - end
    public function business_setting(Request $request)
    {
        // $approved=null;
        //    Code cange by Brijesh on 14-march-22 CR#2 - start
        $label= BusinessSetting::where('is_view', 1)->get();
        //    Code cange by Brijesh on 14-march-22 CR#2 - end
        // dd($label);
        return view('backend.setup_configurations.business_setting',['BusinessSettings'=>$label]);
    }


    public function social_login(Request $request)
    {
        return view('backend.setup_configurations.social_login');
    }

    public function smtp_settings(Request $request)
    {
        return view('backend.setup_configurations.smtp_settings');
    }

    public function google_analytics(Request $request)
    {
        return view('backend.setup_configurations.google_configuration.google_analytics');
    }

    public function google_recaptcha(Request $request)
    {
        return view('backend.setup_configurations.google_configuration.google_recaptcha');
    }
    
    public function google_map(Request $request) {
        return view('backend.setup_configurations.google_configuration.google_map');
    }
    
    public function google_firebase(Request $request) {
        return view('backend.setup_configurations.google_configuration.google_firebase');
    }

    public function facebook_chat(Request $request)
    {
        return view('backend.setup_configurations.facebook_chat');
    }

    public function facebook_comment(Request $request)
    {
        return view('backend.setup_configurations.facebook_configuration.facebook_comment');
    }

    public function payment_method(Request $request)
    {
        return view('backend.setup_configurations.payment_method');
    }

    public function file_system(Request $request)
    {
        return view('backend.setup_configurations.file_system');
    }

    /**
     * Update the API key's for payment methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function payment_method_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', $request->payment_method.'_sandbox')->first();
        if($business_settings != null){
            if ($request->has($request->payment_method.'_sandbox')) {
                $business_settings->value = 1;
                $business_settings->save();
            }
            else{
                $business_settings->value = 0;
                $business_settings->save();
            }
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function google_analytics_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_analytics')->first();

        if ($request->has('google_analytics')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function google_recaptcha_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_recaptcha')->first();

        if ($request->has('google_recaptcha')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function google_map_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_map')->first();

        if ($request->has('google_map')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function google_firebase_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_firebase')->first();

        if ($request->has('google_firebase')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }


    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function facebook_chat_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_chat')->first();

        if ($request->has('facebook_chat')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function facebook_comment_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_comment')->first();
        if(!$business_settings) {
            $business_settings = new BusinessSetting;
            $business_settings->type = 'facebook_comment';
        }

        $business_settings->value = 0;
        if ($request->facebook_comment) {
            $business_settings->value = 1;
        }

        $business_settings->save();

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function facebook_pixel_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_pixel')->first();

        if ($request->has('facebook_pixel')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    /**
     * Update the API key's for other methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function env_key_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    /**
     * overWrite the Env File values.
     * @param  String type
     * @param  String value
     * @return \Illuminate\Http\Response
     */
    public function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');
        
        if(env('DEMO_MODE') != 'On'){
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"'.trim($val).'"';
                if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                    file_put_contents($path, str_replace(
                        $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                    ));
                }
                else{
                    file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
                }
            }
        }
        
    }

    public function update(Request $request)
    {

        foreach ($request->types as $key => $type) {
            if($type == 'site_name'){
                $this->overWriteEnvFile('APP_NAME', $request[$type]);
            }
            if($type == 'timezone'){
                $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
            }
            else {
                $lang = null;
                if(gettype($type) == 'array'){
                    $lang = array_key_first($type);
                    $type = $type[$lang];
                    $business_settings = BusinessSetting::where('type', $type)->where('lang',$lang)->first();
                }else{
                    $business_settings = BusinessSetting::where('type', $type)->first();
                }

                if($business_settings!=null){
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
                else{
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
            }
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }
    // public function business_settingupdate(Request $request)
    // {
    // foreach ($request->types as $key => $type) {
    //     if($type == 'site_name'){
    //         $this->overWriteEnvFile('APP_NAME', $request[$type]);
    //     }
    //     if($type == 'timezone'){
    //         $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
    //     }
    //     else {
    //        // $value = null;
    //        // if(gettype($type) == 'array'){
    //         //    $lang = array_key_first($type);
    //         //    $type = $type[$value];
    //             //$business_settings = BusinessSetting::where('type', $type)->first();
    //        // }else{
    //         //    $business_settings = BusinessSetting::where('type', $type)->first();
    //         //}

    //         if($business_settings!=null){
    //             if(gettype($request[$type]) == 'array'){
    //                 $business_settings->value = json_encode($request[$type]);
    //             }
    //             else {
    //                 $business_settings->value = $request[$type];
    //             }
    //             $business_settings->value = $value;
    //             $business_settings->save();
    //         }
    //         else{
    //             $business_settings = new BusinessSetting;
    //             $business_settings->type = $type;
    //             if(gettype($request[$type]) == 'array'){
    //                 $business_settings->value = json_encode($request[$type]);
    //             }
    //             else {
    //                 $business_settings->value = $request[$type];
    //             }
    //             $business_settings->lang = $lang;
    //             $business_settings->save();
    //         }
    //     }
    // }

    //     Artisan::call('cache:clear');

    //     flash(translate("Settings updated successfully"))->success();
    //     return back();
    // }

    public function updateActivationSettings(Request $request)
    {
        $env_changes = ['FORCE_HTTPS', 'FILESYSTEM_DRIVER'];
        if (in_array($request->type, $env_changes)) {

            return $this->updateActivationSettingsInEnv($request);
        }

        $business_settings = BusinessSetting::where('type', $request->type)->first();
        if($business_settings!=null){
            if ($request->type == 'maintenance_mode' && $request->value == '1') {
                if(env('DEMO_MODE') != 'On'){
                    Artisan::call('down');
                }
            }
            elseif ($request->type == 'maintenance_mode' && $request->value == '0') {
                if(env('DEMO_MODE') != 'On') {
                    Artisan::call('up');
                }
            }
            $business_settings->value = $request->value;
            $business_settings->save();
        }
        else{
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->value;
            $business_settings->save();
        }

        Artisan::call('cache:clear');
        return '1';
    }

    public function updateActivationSettingsInEnv($request)
    {
        if ($request->type == 'FORCE_HTTPS' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 'On');

            if(strpos(env('APP_URL'), 'http:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("http:", "https:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FORCE_HTTPS' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'Off');
            if(strpos(env('APP_URL'), 'https:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("https:", "http:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 's3');
        }
        elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'local');
        }

        return '1';
    }

    public function shipping_configuration(Request $request){
        return view('backend.setup_configurations.shipping_configuration.index');
    }

    public function shipping_configuration_update(Request $request){
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        $business_settings->value = $request[$request->type];
        $business_settings->save();

        Artisan::call('cache:clear');
        return back();
    }
    public function business_settingedit(Request $request)
    {

    //    dd($request->all());
        // $business_settings = BusinessSetting::where('type', $request->type)->first();
       $business_settings = BusinessSetting::where('type', $request->type)->first();
       $business_settings->value = $request->value;
       if($business_settings->save()){
            return 1;
       } else{
            return 0;
       }
        // Artisan::call('cache:clear');
        
    }
    
}
