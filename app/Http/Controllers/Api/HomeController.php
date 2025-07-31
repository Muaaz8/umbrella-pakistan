<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{
    public function getBanners(){
        $banners = DB::table('banner')->where('status',1)->where('platform', 'mobile')->orderBy('sequence','asc')->get();
        foreach ($banners as $banner) {
            $banner->img = \App\Helper::check_bucket_files_url($banner->img);
        }
        return $this->sendResponse($banners, 'Banners retrieved successfully.');
    }
}
