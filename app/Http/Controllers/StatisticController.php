<?php

namespace App\Http\Controllers;

use App\Facades\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function totalLink(Statistic $statistic) {
        return $statistic->totalLink();
    }

    public function todayVisit(Statistic $statistic) {
        return $statistic->todayVisit();
    }

    public function yesterdayVisit(Statistic $statistic) {
        return $statistic->yesterdayVisit();
    }

    public function sevenDaysVisit(Statistic $statistic) {
        return $statistic->sevenDaysVisit();
    }

    public function chart7days(Statistic $statistic) {
        return response(['data' => $statistic->chart7days()], 200);
    }

    public function chart(Statistic $statistic) {
        return response(['data' => $statistic->chart()], 200);
    }
    
    public function getActiveVisitors(Request $request) {
        $userId = Auth::id(); // Lấy ID của user đang đăng nhập

        // Kiểm tra nếu user chưa đăng nhập
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Lấy số lượng người đang truy cập từ cache
        $activeVisitors = Cache::get("active_visitors_{$userId}", 0);

        return response()->json(['active_visitors' => $activeVisitors]);
    }
}
