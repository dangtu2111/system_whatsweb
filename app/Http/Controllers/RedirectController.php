<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectController extends Controller
{
    public function redirect(Request $request)
    {
        $step = Session::get('redirect_step', 1); // Lấy bước chuyển hướng

        $firstRedirect = "https://scontent.fdad3-4.fna.fbcdn.net/v/t39.30808-6/470227724_2947172028794605_3550754267355333831_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGeX3C7NQxyMrxJ15dN4KaMzbkra3R2QZjNuStrdHZBmMpsNzn7GabI0JqRO5v78eyVFUC0ZfHAuhex5Jo-Cyr-&_nc_ohc=fxYIHsxDOu8Q7kNvgFJvkLm&_nc_oc=AdhXqbnRE_vX86npbwo9ZaMlRp-te_kwYR95Ip3FrPV5X_Jc45wC6QVse0ZqThHdh2I&_nc_zt=23&_nc_ht=scontent.fdad3-4.fna&_nc_gid=AIjvqX_G8B3-bHNk6U6euLD&oh=00_AYCCCY3YHvr_DemEByzOGzfZ8dGZ4eAujebJraasvWiqZg&oe=67C0B5D0";
        
        $secondRedirect = "https://origincache-internal-services-all.fbcdn.net/v/t39.30808-6/470227724_2947172028794605_3550754267355333831_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGeX3C7NQxyMrxJ15dN4KaMzbkra3R2QZjNuStrdHZBmMpsNzn7GabI0JqRO5v78eyVFUC0ZfHAuhex5Jo-Cyr-&_nc_ohc=fxYIHsxDOu8Q7kNvgFJvkLm&_nc_oc=AdhXqbnRE_vX86npbwo9ZaMlRp-te_kwYR95Ip3FrPV5X_Jc45wC6QVse0ZqThHdh2I&_nc_zt=23&_nc_ht=scontent.fdad3-4.fna&_nc_gid=AIjvqX_G8B3-bHNk6U6euLD&oh=00_AYCCCY3YHvr_DemEByzOGzfZ8dGZ4eAujebJraasvWiqZg&oe=67C0B5D0";
        dd("ádfasd");
        if ($step == 1) {
            Session::put('redirect_step', 2); // Chuyển sang bước 2
            return redirect($firstRedirect, 302);
        } else {
            Session::forget('redirect_step'); // Xóa trạng thái để tránh vòng lặp vô hạn
            return redirect($secondRedirect, 302);
        }
    }
}
