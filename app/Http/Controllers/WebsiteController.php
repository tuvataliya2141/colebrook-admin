<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeCard;

class WebsiteController extends Controller
{
	public function header(Request $request)
	{
		return view('backend.website_settings.header');
	}
	public function footer(Request $request)
	{	
		$lang = $request->lang;
		return view('backend.website_settings.footer', compact('lang'));
	}
	public function pages(Request $request)
	{
		return view('backend.website_settings.pages.index');
	}
	public function appearance(Request $request)
	{
		return view('backend.website_settings.appearance');
	}
	
	
	public function home_card(Request $request)
	{
		$list  = HomeCard::get();
		return view('backend.website_settings.home_card.index', compact('list'));
	}

	public function home_card_edit($id)
	{
		$card  = HomeCard::findOrFail($id);
		// dd($card);
		return view('backend.website_settings.home_card.edit', compact('card'));
	}

	public function home_card_update(Request $request){

        // dd($request->all());
        $Banner = HomeCard::findOrFail($request->card_id);
        $Banner->url = $request->url;
        $Banner->title = $request->title;
        $Banner->image = $request->image;
        $Banner->save();
        flash(translate('Banner has been updated successfully'))->success();
        return redirect()->route('website.home_card');
    }
}