<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Jenssegers\Agent\Agent;
use Auth;
use App\Exports\LinksExport;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use App\DestinationUrl;
class DomainController extends Controller 
{
	// public function index(Request $request) 
	// {
 	// 	$type = request()->type ?? '';
 	// 	$user = request()->user ?? '';
	// 	$links = Domain::orderBy('created_at', 'desc');

    //     if(user_member())
    //         $links = $links->whereUserId(optional(user())->id);

    //     if(is_backend() && $user)
    //         $links = $links->whereUserId($user);

	// 	// if($type) {
	// 	// 	$links = $links->whereType($type);
	// 	// }

	// 	if($request->is('*export*')) {
	// 		if(is_demo()) return abort(403);

	//         return Excel::download(new LinksExport($links->get()), 'links.' . $request->format ?? 'xlsx', constant(sprintf('%s::%s', \Maatwebsite\Excel\Excel::class, strtoupper($request->format ?? 'xlsx'))));
	// 	}

	// 	$links = $links->paginate(10);
		
	// 	return view('domain.index', compact('links', 'type'));
	// }
	public function index(Request $request) 
	{
 		$type = request()->type ?? '';
 		$user = request()->user ?? '';
		$links = Domain::orderBy('created_at', 'desc');

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
		
		return view('domain.index', compact('links', 'type'));
	}

	public function show(Request $request)
	{
		$id = decrypt($request->id);
		$link = Domain::find($id);

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
		$link = Domain::find($id);

		$id = encrypt($id);
		$title = 'Edit Link';
		return view('domain.create', compact('link', 'id', 'title'));
	}

	public function create() 
	{
		$title = 'Create New Link';
		return view('domain.create', compact('title'));
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

		$link = Domain::find($id);

        $data = [
			'user_id' => optional(user())->id ?? NULL,
			'name' => $request->input('name_phone'),
			'slug' => preg_replace('/^www\./', '', parse_url($request->input('url'), PHP_URL_HOST)),
			'is_active' => true
		];
		
		// Cập nhật nếu domain đã tồn tại
		$link->update($data);
		
		 
		
		

		// $link = $this->_result($link->slug, $link->type);

		return response([
			'success' => true,
			'data' => $link
		], 200);
	}

	public function store(Request $request) 
	{
		try {
			// Validate request
			if ($request->type == 'WHATSAPP') {
				$this->_validator($request);
			} else {
				$this->_validator($request, false, [
					'url' => 'required'
				], ['phone_code', 'phone_number', 'content']);
			}

			// Lấy slug từ URL
			$slug = parse_url($request->input('url'), PHP_URL_HOST);

			// Kiểm tra domain đã tồn tại chưa
			if (Domain::where('slug', $slug)->exists()) {
				return response()->json([
					'success' => false,
					'message' => 'Domain này đã tồn tại!',
					'error_code' => 1062 // Mã lỗi trùng khóa
				], 400);
			}

			// Tạo mới domain
			$link = Domain::create([
				'user_id'   => optional(user())->id ?? null, 
				'name'      => $request->input('name_phone'),
				'slug'      => $slug,
				'is_active' => true
			]);

			// Kiểm tra nếu tạo thất bại
			if (!$link) {
				return response()->json([
					'success' => false,
					'message' => 'Không thể tạo mới domain. Vui lòng thử lại!'
				], 400);
			}

			return response()->json([
				'success' => true,
				'data' => $link
			], 200);

		} catch (\Illuminate\Database\QueryException $e) {
			if ($e->errorInfo[1] == 1062) { // Lỗi Duplicate entry
				return response()->json([
					'success' => false,
					'message' => 'Domain này đã tồn tại!',
					'error_code' => 1062
				], 400);
			}

			return response()->json([
				'success' => false,
				'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
			], 500);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
			], 500);
		}
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
		$link = Domain::whereSlug($id)->first();
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
		$link = Domain::find($id);
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
        $metaTags =json_decode($response->getBody(), true);

        return $metaTags;
    }


	public function slug($slug)
	{
		$link = Domain::whereSlug($slug)->first();

		if(!isset($link)) return abort(404);

		$link->update([
			'hit' => $link->hit + 1
		]);

		$ip = \Request::ip();

		$agent = new Agent();

		
	

		if($link->type == 'WHATSAPP')
			$link = 'https://api.whatsapp.com/send?phone='.$link->phone_number.'&text=' . rawurlencode($link->content);
		else
			$link = $link->url;
		$config = $this->fetchMetaTags($link);

		
		return view('view', compact('link','config'));
	}
}
