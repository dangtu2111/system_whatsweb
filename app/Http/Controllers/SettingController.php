<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use anlutro\LaravelSettings\Facade as Setting;

class SettingController extends Controller
{
	public function index(Request $request) {
		$type = $request->type ?? 'general';

		return view('settings.index', compact('type'));
	}

	public function update(Request $request, $type) {
        if(is_demo()) return abort(403);

		if($type == 'general') {
			$this->validate($request, [
				'site_name' => 'required',
				'site_tagline' => 'required',
				'site_country' => 'required',
				'site_logo' => 'required',
			]);

			setting([
				'general.site_name' => $request->site_name,
				'general.site_tagline' => $request->site_tagline,
				'general.site_country' => $request->site_country,
				'general.site_logo' => $request->site_logo
			])->save();
		}else if($type == 'features') {
			$this->validate($request, [
				'login_with_google' => 'required',
				'login_with_facebook' => 'required',
				'open_register' => 'required',
				'custom_slug' => 'required',
				'custom_slug_min' => 'required',
				'custom_update_min' => 'required',
				'custom_slug_max' => 'required',
				'qr_code_size' => 'required|numeric',
				'shortlink_button_image' => 'required',
				'shortlink_button_alt' => 'required',
				'whatsapp_button_image' => 'required',
				'whatsapp_button_alt' => 'required',
			]);

			setting([
				'features.login_with_google' => $request->login_with_google,
				'features.login_with_facebook' => $request->login_with_facebook,
				'features.open_register' => $request->open_register,
				'features.custom_slug' => $request->custom_slug,
				'features.custom_slug_min' => $request->custom_slug_min,
				'features.custom_update_min' => $request->custom_update_min,
				'features.custom_slug_max' => $request->custom_slug_max,
				'features.qr_code_size' => $request->qr_code_size,
				'features.shortlink_button_image' => $request->shortlink_button_image,
				'features.shortlink_button_alt' => $request->shortlink_button_alt,
				'features.whatsapp_button_image' => $request->whatsapp_button_image,
				'features.whatsapp_button_alt' => $request->whatsapp_button_alt,
			])->save();
		}else if($type == 'seo') {
			$this->validate($request, [
				'description' => 'required',
				'keywords' => 'required',
				'image' => 'required',
				'home_h1' => 'required',
				'home_description' => 'required',
			]);

			setting([
				'seo.image' => $request->image,
				'seo.description' => $request->description,
				'seo.keywords' => $request->keywords,
				'seo.home_h1' => $request->home_h1,
				'seo.home_description' => $request->home_description,
			])->save();
		}else if($type == 'integration') {
			$this->validate($request, [
				'google_analytics' => 'required',
				'facebook_pixel' => 'required',
				'before_head' => 'required',
				'before_body' => 'required',
			]);

			setting([
				'integration.google_analytics' => $request->google_analytics,
				'integration.facebook_pixel' => $request->facebook_pixel,
				'integration.before_head' => $request->before_head,
				'integration.before_body' => $request->before_body,
			])->save();
		}

		return response([
			'success' => true,
			'message' => 'Setting saved successfully' 
		], 200);
	}
}
