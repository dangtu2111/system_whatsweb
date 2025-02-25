<?php
namespace App\Facades;

use App\Link;
use App\User;
use App\Stat;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class Statistic {
	public function totalLink()
	{
		$link = new Link;

		if(user_member()) $link = $link->whereUserId(user_member());

		$link = $link->count();

		return $link;
	}

	public function myLink()
	{
		$link = new Link;
		if(user_member()) $link = $link->whereUserId(user_member());
		$link = $link->get();
		return $link;
	}
	
	public function myUser()
	{
		$user = new User;
		if (user_member()) {
			$user = $user->where('id', user_member());
		}
		return $user->get();
	}


	public function todayVisit()
	{
		return $this->_dateVisit(stat_date('=', date('Y-m-d')));
	}

	public function yesterdayVisit()
	{
		return $this->_dateVisit(stat_date('=', Carbon::yesterday()->format('Y-m-d')));
	}

	public function sevenDaysVisit()
	{
		return $this->_dateVisit(stat_date('>=', Carbon::today()->subDays('7')));
	}

	public function chart7days()
	{
		return $this->_baseChart(Carbon::today()->subDays('7'), date('Y-m-d'));
	}

	public function chart()
	{
		$date = request()->date;
		$date = explode(" - ", $date);
		$link = request()->link ? decrypt(request()->link) : false;
		return $this->_baseChart($date[0], $date[1], $link);
	}

	private function _baseChart($from, $to, $link=false)
	{
		$stat = new Stat;

		if(user_member()) $stat = $stat->whereUsersId(user_member());

		if($link) $stat = $stat->whereLinksId($link);

		$raw_stat = $stat->selectRaw('*, count(*) as count')->whereRaw('date(created_at) between ? and ?', [$from, $to]);

		$stat = $raw_stat->groupBy(DB::raw('date(created_at)'))->get();

		$iteratation = CarbonPeriod::create($from, $to);

		$labels = [];
		$values = [];
		foreach($iteratation as $d) {
			$values[$d->format('Y-m-d')] = 0;
			$labels[] = $d->format('Y-m-d');
		}

		foreach($stat as $s) {
			$values[$s->created_at->format('Y-m-d')] = $s->count;
		}

		$output = collect($values)->values();

		$referer_stat = clone $raw_stat;
		$referer = $referer_stat->groupBy('referer')->orderBy('count', 'desc')->get();
		$device_stat = clone $raw_stat;
		$device = $device_stat->groupBy('device')->orderBy('count', 'desc')->get();
		$platform_stat = clone $raw_stat;
		$platform = $platform_stat->groupBy('platform')->orderBy('count', 'desc')->get();
		$browser_stat = clone $raw_stat;
		$browser = $browser_stat->groupBy('browser')->orderBy('count', 'desc')->get();

		return (object) [
			'labels' => json_encode($labels),
			'values' => json_encode($output),
			'stats' => [
				'referer' => $referer,
				'device' => $device,
				'platform' => $platform,
				'browser' => $browser,
			]
		];
	}
	public function chart1()
	{
		$date = request()->date;
		$date = explode(" - ", $date);
		$user = request()->link ? decrypt(request()->link) : false;
		return $this->_baseChart1($date[0], $date[1], $user);
	}
	public function member(){
		$raw_stat = Stat::leftJoin('users', 'stats.users_id', '=', 'users.id')
			->selectRaw('users.id as userid, users.name, COUNT(stats.id) as total_stats')
			->groupBy('users.id', 'users.name')
			->get();

	
		return response()->json($raw_stat);
	}
	

	private function _baseChart1($from, $to, $user=false)
	{
		$stat = new Stat;

		if(user_member()) $stat = $stat->whereUsersId(user_member());
		

		if($user) $stat = $stat->where('users_id', $user);

		// Thực hiện join với bảng users để lấy user.name
		$raw_stat = $stat->leftJoin('users', 'stats.users_id', '=', 'users.id')
		->selectRaw('stats.*, users.name, count(*) as count')
		->whereRaw('date(stats.created_at) between ? and ?', [$from, $to]);

		$stat = $raw_stat->groupBy(DB::raw('date(stats.created_at)'))->get();

		$iteratation = CarbonPeriod::create($from, $to);

		$labels = [];
		$values = [];
		foreach($iteratation as $d) {
			$values[$d->format('Y-m-d')] = 0;
			$labels[] = $d->format('Y-m-d');
		}

		foreach($stat as $s) {
			$values[$s->created_at->format('Y-m-d')] = $s->count;
		}

		$output = collect($values)->values();

		$userTable_stat = clone $raw_stat;
		$userTable = $userTable_stat->groupBy('DATE(stats.created_at)')
		->selectRaw('DATE(stats.created_at), users.name, COUNT(stats.id) as count') // Tính tổng số stat theo ngày
		->orderBy('count', 'desc')
		->get();

		$referer_stat = clone $raw_stat;
		$referer = $referer_stat->groupBy('referer')->orderBy('count', 'desc')->get();
		$device_stat = clone $raw_stat;
		$device = $device_stat->groupBy('device')->orderBy('count', 'desc')->get();
		$platform_stat = clone $raw_stat;
		$platform = $platform_stat->groupBy('platform')->orderBy('count', 'desc')->get();
		$browser_stat = clone $raw_stat;
		$browser = $browser_stat->groupBy('browser')->orderBy('count', 'desc')->get();

		return (object) [
			'labels' => json_encode($labels),
			'values' => json_encode($output),
			'stats' => [
				'userTable'=>$userTable,
				'referer' => $referer,
				'device' => $device,
				'platform' => $platform,
				'browser' => $browser,
			]
		];
	}

	public function top($take) {
		$link = Link::orderBy('hit', 'desc')->take($take);

		if(user_member()) $link = $link = $link->whereUserId(user_member());

		$link = $link->get();

		return $link;
	}

	private function _dateVisit($date, $method='count')
	{
		$stat = new Stat;
		$stat = $stat->leftJoin('links', 'stats.links_id', 'links.id');
		$stat = $stat->selectRaw('count(stats.links_id) as count');
		$stat = $stat->whereRaw('date(stats.created_at) ' . $date);
		

		if(user_member())
			$stat = $stat->whereRaw('stats.users_id  = ' . user_member());

		$stat = $stat->{$method}();

		return $stat;
	}
	

	public function getActiveVisitors()
	{
		$userId = user_member();
		
		if (!$userId) {
			return 0;
		}

		// Lấy danh sách visitor từ cache (nếu chưa có, mặc định là mảng rỗng)
		$cacheKey = "active_visitors-{$userId}";
		$activeVisitors = Cache::get($cacheKey, []);
		
		// Xóa session cũ hơn 5 phút
		$now = Carbon::now()->timestamp;
		$activeVisitors = array_filter($activeVisitors, function ($timestamp) use ($now) {
			return $timestamp > ($now - 180);
		});

		// Cập nhật lại cache với thời gian hết hạn là 5 phút
		Cache::put($cacheKey, $activeVisitors, now()->addMinutes(5));

		return count($activeVisitors);
	}
	public function thisMonthVisit() {
		return $this->_dateVisit(stat_date('>=', Carbon::now()->startOfMonth()->format('Y-m-d')));
	}
	public function lastMonthVisit() {
		$startOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
		$endOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
	
		return $this->_dateVisit("BETWEEN '$startOfLastMonth' AND '$endOfLastMonth'");
	}
	
	
	
	
}