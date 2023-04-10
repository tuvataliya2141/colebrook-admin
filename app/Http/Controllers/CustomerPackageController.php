<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerPackage;
use App\Models\CustomerPackageTranslation;
use App\Models\CustomerPackagePayment;
use Auth;
use Session;
use App\Models\User;
use App\Http\Controllers\RazorpayController;

class CustomerPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_packages = CustomerPackage::all();
        return view('backend.customer.customer_packages.index', compact('customer_packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.customer.customer_packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer_package = new CustomerPackage;
        $customer_package->name = $request->name;
        $customer_package->amount = $request->amount;
        $customer_package->product_upload = $request->product_upload;
        $customer_package->logo = $request->logo;

        $customer_package->save();

        $customer_package_translation = CustomerPackageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'customer_package_id' => $customer_package->id]);
        $customer_package_translation->name = $request->name;
        $customer_package_translation->save();


        flash(translate('Package has been inserted successfully'))->success();
        return redirect()->route('customer_packages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $customer_package = CustomerPackage::findOrFail($id);
        return view('backend.customer.customer_packages.edit', compact('customer_package', 'lang'));
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
        $customer_package = CustomerPackage::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $customer_package->name = $request->name;
        }
        $customer_package->amount = $request->amount;
        $customer_package->product_upload = $request->product_upload;
        $customer_package->logo = $request->logo;

        $customer_package->save();

        $customer_package_translation = CustomerPackageTranslation::firstOrNew(['lang' => $request->lang, 'customer_package_id' => $customer_package->id]);
        $customer_package_translation->name = $request->name;
        $customer_package_translation->save();

        flash(translate('Package has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer_package = CustomerPackage::findOrFail($id);
        foreach ($customer_package->customer_package_translations as $key => $customer_package_translation) {
            $customer_package_translation->delete();
        }
        CustomerPackage::destroy($id);

        flash(translate('Package has been deleted successfully'))->success();
        return redirect()->route('customer_packages.index');
    }

    public function purchase_payment_done($payment_data, $payment)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->customer_package_id = $payment_data['customer_package_id'];
        $customer_package = CustomerPackage::findOrFail($payment_data['customer_package_id']);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();

        flash(translate('Package purchasing successful'))->success();
        return redirect()->route('dashboard');
    }

    public function purchase_package_offline(Request $request)
    {
        $customer_package = new CustomerPackagePayment;
        $customer_package->user_id = Auth::user()->id;
        $customer_package->customer_package_id = $request->package_id;
        $customer_package->payment_method = $request->payment_option;
        $customer_package->payment_details = $request->trx_id;
        $customer_package->approval = 0;
        $customer_package->offline_payment = 1;
        $customer_package->reciept = ($request->photo == null) ? '' : $request->photo;
        $customer_package->save();
        flash(translate('Offline payment has been done. Please wait for response.'))->success();
        return redirect()->route('customer_products.index');
    }
}
