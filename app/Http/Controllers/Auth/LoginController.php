<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\UserProvider;

use Auth;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/backend';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider)
    {
        if(!setting('features.login_with_' . $provider)) {
            return redirect()->route('login');
        }
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        $authUser = $this->findOrCreateUser($user, $provider);

        Auth::login($authUser, true);

        return user_redirect($authUser->type);
    }

    public function findOrCreateUser($user_data, $provider)
    {
        $auth_user = User::whereHas('providers', function($query) use($user_data, $provider) {
            $query->where('provider', $provider);
            $query->where('provider_id', $user_data->id);
        })->first();

        // If user exist by provider
        if($auth_user) {
            $user = $auth_user;
        }

        // check user by email (if user has no provider)
        $find_email = User::whereEmail($user_data->email)->first();

        // if user not found by email
        if(!$find_email) {
            // create user
            $user = User::create([
                'name'     => $user_data->name,
                'email'    => $user_data->email,
                'type'    => 'MEMBER',
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
        }else{
            $user = $find_email;
        }

        $user_provider = UserProvider::whereUserId($user->id)->whereProvider($provider)->whereProviderId($user_data->id)->first();

        if(!$user_provider) {
            $user_provider = UserProvider::create([
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_id' => $user_data->id,
            ]);
        }

        return $user;
    }
}
