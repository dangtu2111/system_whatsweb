<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Session;
use Mail;
use App\Mail\PasswordChangedMail;

class SettingController extends Controller
{
	public function index() 
	{
		return view('dashboard.settings.index');
	}

	/**
	 * Update user account information
	 * @param  Request $request
	 * @return redirect
	 */
	public function update(Request $request)
	{
		if(config('whatsweb.demo') && user()->id == 1) return abort(403);

		$this->validate($request, [
			'name' => 'required|max:40',
			'password' => 'nullable|min:6'
		]);

		$input = $request->all();

		$is_pass_changed = false;

		if($input['password']) {
			$is_pass_changed = true;
			$input['password'] = bcrypt($input['password']);
		}else{
			$input['password'] = user()->password;
		}

		User::find(user()->id)->update($input);

		if($is_pass_changed) {
			Mail::to(user()->email)->send(new PasswordChangedMail(user()));
		}

		return response([
			'success' => true,
			'message' => 'Your account information has been updated'
		], 200);
	}
}
