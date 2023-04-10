<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;


class PageController extends Controller
{
    public function homeBannerList(){
        $list = Banner::get();
        return view('backend.setup_configurations.banner.index', compact('list'));
    }

    public function homeBannerAdd(){
        // $list = Banner::get();
        return view('backend.setup_configurations.banner.create');
    }

    public function homeBannerStore(Request $request){

        // dd('hii');
        $Banner = new Banner;
        $Banner->url = $request->url;
        $Banner->title = $request->title;
        $Banner->sub_title = $request->sub_title;
        $Banner->photo = $request->photo;
        $Banner->save();
        flash(translate('Banner has been inserted successfully'))->success();
        return redirect()->route('home-banner.list');
    }

    public function homeBannerEdit($id){
        $banner = Banner::where('id', $id)->first();
        // dd($banner);
        return view('backend.setup_configurations.banner.edit', compact('banner'));
    }

    public function homeBannerUpdate(Request $request){

        // dd($request->all());
        $Banner = Banner::findOrFail($request->banner_id);
        $Banner->url = $request->url;
        $Banner->title = $request->title;
        $Banner->sub_title = $request->sub_title;
        $Banner->photo = $request->photo;
        $Banner->save();
        flash(translate('Banner has been updated successfully'))->success();
        return redirect()->route('home-banner.list');
    }

    public function homeBannerDelete($id){
        // dd('hii');
        $banner = Banner::where('id', $id)->delete();
        flash(translate('Banner has been deleted successfully'))->success();
        return redirect()->route('home-banner.list');
    }
}
