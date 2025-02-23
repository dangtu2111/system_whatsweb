<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    public function show($filename)
    {
        $path = pathinfo($filename);
        preg_match_all('/thumbs-(.*)/i', $path['dirname'], $thumbs);

        if ($path['dirname'] !== '.' && ($path['dirname'] !== config('midia.directory_name'))) {
            foreach (config('midia.directories') as $d) {
                if ($d['name'] == rtrim(preg_replace('/thumbs-(.*)/i', '', $path['dirname']), "/")) {
                    if (strpos($path['dirname'], "thumbs-") == false) {
                        $path = $d['path'] . '/' . $path['basename'];
                    } else {
                        $path = $d['path'] . '/' . ltrim($thumbs[0][0], "/") . '/' . $path['basename'];
                    }
                    break;
                }
            }

            if (is_array($path)) {
                if (strpos($path['dirname'], "thumbs-") == false) {
                    $path = config('midia.directory') . '/' . $filename;
                } else {
                    $path = '';
                }
            }
        } else {
            $path = config('midia.directory') . '/' . $filename;
        }

        if (!File::exists($path)) return abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);

        // Resize ảnh nếu có width/height
        $width = request()->width;
        $height = request()->height;

        if ($width || $height) {
            if (!$width) $width = $height;
            if (!$height) $height = $width;

            $image = Image::make($path);
            $image->fit($width, $height);
            return $image->response();
        }

        return response($file, 200)->header("Content-Type", $type);
    }
}
