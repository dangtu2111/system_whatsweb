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
        return response(['data' => $statistic->chart1()], 200);
    }
    
    public function getActiveVisitors(Statistic $statistic) {
        return $statistic->getActiveVisitors();
    }
    public function thisMonthVisit(Statistic $statistic)
    {
        return $statistic->thisMonthVisit();
    }

    public function lastMonthVisit(Statistic $statistic)
    {
        return $statistic->lastMonthVisit();
    }

}
