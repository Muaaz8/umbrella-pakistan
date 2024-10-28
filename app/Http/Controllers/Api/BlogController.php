<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Blogs;
use DB;

class BlogController extends BaseController
{
   public function blog_store(Request $request){
        $title = $request->title;
        $slug = \Str::slug($title);
        $featured_image = $request->featured_image;
        $status = $request->status;
        $content = $request->content;
        $blog = DB::table('blogs')->insert(['title' => $title, 'slug'=> $slug , 'featured_image' => $featured_image, 'content' => $content, 'status' => $status]);
        return $this->sendResponse(200,"blog inserted");
   }
}

