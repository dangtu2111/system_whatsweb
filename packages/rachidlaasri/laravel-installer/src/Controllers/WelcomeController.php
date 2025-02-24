<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User; 

class WelcomeController extends Controller
{

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('vendor.installer.welcome');
    }
    public function debug(){
        $users = User::all(); // Lấy tất cả dữ liệu từ bảng users
        dd($users);
    }

}
