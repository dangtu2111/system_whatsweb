<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Link;
use App\Stat;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Jenssegers\Agent\Agent;
use Auth;
use App\Exports\LinksExport;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Maatwebsite\Excel\Facades\Excel;

class LinkController extends Controller
{
	public function index(Request $request) 
	{
 		$type = request()->type ?? '';
 		$user = request()->user ?? '';
		$links = Link::orderBy('created_at', 'desc');

        if(user_member())
            $links = $links->whereUserId(optional(user())->id);

        if(is_backend() && $user)
            $links = $links->whereUserId($user);

		if($type) {
			$links = $links->whereType($type);
		}

		if($request->is('*export*')) {
			if(is_demo()) return abort(403);

	        return Excel::download(new LinksExport($links->get()), 'links.' . $request->format ?? 'xlsx', constant(sprintf('%s::%s', \Maatwebsite\Excel\Excel::class, strtoupper($request->format ?? 'xlsx'))));
		}

		$links = $links->paginate(10);
		return view('links.index', compact('links', 'type'));
	}

	public function show(Request $request)
	{
		$id = decrypt($request->id);
		$link = Link::find($id);

		$link = $this->_result($link->slug, $link->type);

		return response([
			'success' => true,
			'data' => $link
		], 200);
	}

	public function edit($id)
	{
		if(is_demo()) return abort(403);
		
		$id = decrypt($id);
		$link = Link::find($id);

		$id = encrypt($id);
		$title = 'Edit Link';
		return view('links.create', compact('link', 'id', 'title'));
	}

	public function create() 
	{
		$title = 'Create New Link';
		return view('links.create', compact('title'));
	}

	private function _validator($request, $id=false, $adds=false, $excepts=false) {
		$id = ',' . $id ?? '';

		$validate = [
			'phone_code' => 'required',
			'phone_number' => 'required|min:8|max:30',
			'content' => 'required',
			'slug' => 'nullable|unique:links,slug'.$id.'|min:'.setting('features.custom_slug_min').'|max:' . setting('features.custom_slug_max')
		];
		if($adds && is_array($adds)) {
			$validate += $adds;
		}
		if($excepts && is_array($excepts)) {
			$validate = array_except($validate, $excepts);
		}
		return $this->validate($request, $validate);
	}

	public function update(Request $request, $id) {
		if(is_demo()) return abort(403);

		$id = decrypt($id);
		if($request->type == 'WHATSAPP')
			$this->_validator($request, $id);
		else
			$this->_validator($request, $id, [
				'url' => 'required'
			], ['phone_code', 'phone_number', 'content']);

		$link = Link::find($id);

		$input = $request->all();
		$link->update($input);

		$link = $this->_result($link->slug, $link->type);

		return response([
			'success' => true,
			'data' => $link
		], 200);
	}

	public function store(Request $request) 
	{
		if($request->type == 'WHATSAPP')
			$this->_validator($request);
		else
			$this->_validator($request, false, [
				'url' => 'required'
			], ['phone_code', 'phone_number', 'content']);

		$phone_number = $request->phone_code . $request->phone_number;
		$slug = str_random(setting('features.custom_slug_max'));

		if(setting('features.custom_slug')) {
			if(isset($request->slug) && trim($request->slug)) {
				$slug = $request->slug;
			}
		}

		$link = Link::create([
			'phone_code' => $request->phone_code ?? NULL,
			'phone_number' => $phone_number ?? NULL,
			'slug' => $slug,
			'content' => $request->content ?? NULL,
			'user_id' => optional(user())->id ?? NULL,
			'type' => $request->type ?? 'WHATSAPP',
			'url' => $request->url ?? NULL
		]);

		$link = $this->_result($slug, $link->type);

		return response([
			'success' => true,
			'data' => $link
		], 200);
	}

	private function _result($slug, $type='WHATSAPP') 
	{
		$link['generated_link'] = url($slug);
		$link['html_link'] = '<a href="' . url($slug) . '" target="_blank"><img src="'. media(setting('features.'.strtolower($type)).'_button_image') .'" alt="'. setting('features.'.strtolower($type).'_button_alt') .'"></a>';
		$link['qrcode'] = route('qrcode', [$slug]);
		$link['qrcode_save'] = route('qrcode', [$slug, 'save']);
		$link['share_facebook'] = 'https://facebook.com/share.php?u=' . url($slug);
		$link['share_twitter'] = 'https://twitter.com/intent/tweet?status=' . url($slug);
		$link['share_whatsapp'] = 'https://api.whatsapp.com/send?text=' . url($slug);
		$link['share_telegram'] = 'https://telegram.me/share/url?url=' . url($slug);

		return $link;
	}

	public function qrcode($id, $action=false) {		
		$link = Link::whereSlug($id)->first();
		if(!isset($link)) {
			return abort(404);
		}

		$response = QrCode::format('png')->margin(1)->size(setting('features.qr_code_size'));
		if($action == 'save') {
			$response = $response->generate(route('slug', [$link->slug]), storage_path('media/' . $link->slug . '.png'));
			$response = response()
						->download(storage_path('media/' . $link->slug . '.png'))->deleteFileAfterSend(true);
		}else{
			$response = response($response->generate(route('slug', [$link->slug])));
			$response = $response->header('Content-Type', 'image/png');
		}

		return $response;
	}

	public function destroy($id)
	{
		if(is_demo()) return abort(403);

		$id = decrypt($id);
		$link = Link::find($id);
		$link->delete();

		Stat::whereLinkId($id)->delete();

		return redirect()->back()->with('delete', true);
	}
	public function fetchOgMeta($url)
	{
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			return ['error' => 'URL không hợp lệ'];
		}

		try {
			$client = new Client();
			$response = $client->get($url);
			$html = (string) $response->getBody();

			$crawler = new Crawler($html);

			return [
				'og:title' => $crawler->filterXpath('//meta[@property="og:title"]')->attr('content') ?? '',
				'og:description' => $crawler->filterXpath('//meta[@property="og:description"]')->attr('content') ?? '',
				'og:image' => $crawler->filterXpath('//meta[@property="og:image"]')->attr('content') ?? '',
				'og:url' => $crawler->filterXpath('//meta[@property="og:url"]')->attr('content') ?? '',
				'og:type' => $crawler->filterXpath('//meta[@property="og:type"]')->attr('content') ?? '',
			];
		} catch (\Exception $e) {
			return ['error' => 'Không thể lấy dữ liệu'];
		}
	}

	public function slug($slug)
	{
		$link = Link::whereSlug($slug)->first();

		if(!isset($link)) return abort(404);

		$link->update([
			'hit' => $link->hit + 1
		]);

		$ip = \Request::ip();

		$agent = new Agent();

		$stat = [
			'users_id' => $link->user_id,
			'links_id' => $link->id,
			'ip' => $ip,
			'user_agent' => request()->server('HTTP_USER_AGENT'),
			'referer' => request()->server('HTTP_REFERER'),
			'device' => (
				$agent->isMobile() ? 'MOBILE' : 
				($agent->isTablet() ? 'TABLET' : 
				($agent->isDesktop() ? 'DESKTOP' : ''))
			),

			'device_name' => $agent->device(),
			'browser' => $agent->browser(),
			'browser_version' => $agent->version($agent->browser()),
			'platform' =>  $agent->platform(),
			'platform_version' =>  $agent->version($agent->platform()),
		];
		Stat::create($stat);

		if($link->type == 'WHATSAPP')
			$link = 'https://api.whatsapp.com/send?phone='.$link->phone_number.'&text=' . rawurlencode($link->content);
		else
			$link = $link->url;
		$config = $this->fetchOgMeta($link);
		
		return view('view', compact('link','config'));
	}
}
