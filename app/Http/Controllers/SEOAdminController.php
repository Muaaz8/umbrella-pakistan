<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SEOAdminController extends Controller
{
    public function seo_admin_dash()
    {
        $medicines = DB::table('tbl_products')->where('mode','medicine')->orderby('name')->get()->toArray();
        $imagings = DB::table('tbl_products')->where('mode','imaging')->orderby('name')->get()->toArray();
        $labs = DB::table('quest_data_test_codes')
        ->where('AOES_exist', null)
        ->where([
            ['TEST_NAME', '!=', ""],
            ['PARENT_CATEGORY', '!=', ""],
            ['DETAILS', '!=', ""], /* WILL REMOVE */
            ['SALE_PRICE', '!=', ""],
        ])
        ->orderby('TEST_NAME')
        ->get()->toArray();
        $medicines = json_encode($medicines);
        $imagings = json_encode($imagings);
        $labs = json_encode($labs);
        $tags = DB::table('meta_tags')->where('url',null)->paginate(9);
        $data = DB::table('meta_tags')->where('url',null)->get()->toArray();
        $data = json_encode($data);
        return view('dashboard_SEO.seo_admin',compact('medicines','imagings','labs','tags','data'));
    }

    public function save_meta_tag(Request $request)
    {
        $pro_name;
        if($request->pro_mode=='lab-test')
        {
            $prod = DB::table('quest_data_test_codes')->where('TEST_CD',$request->pro_id)->first();
            $pro_name = $prod->DESCRIPTION;
        }
        else
        {
            $prod = DB::table('tbl_products')->where('id',$request->pro_id)->first();
            $pro_name = $prod->name;
        }
        DB::table('meta_tags')->insert([
            'product_id'=>$request->pro_id,
            'product_mode'=>$request->pro_mode,
            'pro_name'=>$pro_name,
            'name'=>'title',
            'content'=>$request->title,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        DB::table('meta_tags')->insert([
            'product_id'=>$request->pro_id,
            'product_mode'=>$request->pro_mode,
            'pro_name'=>$pro_name,
            'name'=>'description',
            'content'=>$request->description,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        DB::table('meta_tags')->insert([
            'product_id'=>$request->pro_id,
            'product_mode'=>$request->pro_mode,
            'pro_name'=>$pro_name,
            'name'=>'keywords',
            'content'=>$request->keywords,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('Tag Entered Successfully');
    }

    public function seo_admin_acc_setting()
    {
        return view('dashboard_SEO.AccountSetting.index');
    }

    public function pages_meta_tag()
    {
        $tags = DB::table('meta_tags')->where('product_id',null)->paginate(9);
        return view('dashboard_SEO.website_pages_title',compact('tags'));
    }

    public function pages()
    {
        $pages = DB::table('pages')->paginate(10);
        return view('dashboard_SEO.website_pages',compact('pages'));
    }
    public function edit_page($id){
        $pages = DB::table('pages')->where('id',$id)->first();
        return view('dashboard_SEO.website_page_edit',compact('pages'));
    }

    public function update_page($id,Request $request){
        $pages = DB::table('pages')->where('id',$id)->first();
        if($pages){
            DB::table('pages')->where('id',$id)->update([
                'name'=>$request->page_name,
                'url'=>$request->url,
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->route('pages');
    }

    public function top_banner()
    {
        $top_banner = DB::table('services')->where('name','ticker')->paginate(10);
        return view('dashboard_SEO.top_banner.index',compact('top_banner'));
    }

    public function save_top_banner(Request $request)
    {
        $top_banner = DB::table('services')->insert([
            'name' => 'ticker',
            'status' => $request->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('top_banner');
    }

    public function edit_top_banner($id){
        $top_banner = DB::table('services')->where('id',$id)->first();
        return view('dashboard_SEO.top_banner.edit',compact('top_banner'));
    }

    public function update_top_banner($id,Request $request){
        $pages = DB::table('services')->where('id',$id)->first();
        if($pages){
            DB::table('services')->where('id',$id)->update([
                'status' => $request->value,
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('top_banner');
    }


    public function pages_section()
    {
        $pages = DB::table('pages')->get();
        $section = DB::table('section')->join('pages','pages.id','section.page_id')->select('section.*','pages.name as page_name')->paginate(10);
        return view('dashboard_SEO.website_pages_section',compact('pages','section'));
    }

    public function edit_section($id){
        $pages = DB::table('pages')->get();
        $section = DB::table('section')->where('id',$id)->first();
        return view('dashboard_SEO.website_section_edit',compact('pages','section'));
    }

    public function update_section($id,Request $request){
        $section = DB::table('section')->where('id',$id)->first();
        if($section){
            DB::table('section')->where('id',$id)->update([
                'page_id'=>$request->page_id,
                'section_name'=>$request->section_name,
                'sequence_no'=>$request->sequence_no,
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->route('pages_section');
    }

    public function pages_section_content()
    {
        $pages = DB::table('pages')->get();
        $section = DB::table('section')->get();
        $contents = DB::table('content')
            ->join('section','section.id','content.section_id')
            ->join('pages','pages.id','section.page_id')
            ->select('content.*','section.section_name as section_name','pages.name as page_name')
            ->paginate(10);
        return view('dashboard_SEO.website_pages_content',compact('pages','section','contents'));
    }

    public function upload_image_endpoint(Request $request)
    {
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->move(public_path('uploads'), $filename);
            $url = asset('uploads/' . $filename);
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }

    public function pages_image_content()
    {
        $pages = DB::table('pages')->get();
        $section = DB::table('section')->get();
        $contents = DB::table('images_content')
            ->join('section','section.id','images_content.section_id')
            ->join('pages','pages.id','section.page_id')
            ->select('images_content.*','section.section_name as section_name','pages.name as page_name')
            ->paginate(10);
        return view('dashboard_SEO.website_pages_image_content',compact('pages','section','contents'));
    }

    public function edit_content($id){
        $pages = DB::table('pages')->get();
        $section = DB::table('section')->get();
        $content = DB::table('content')
            ->join('section','section.id','content.section_id')
            ->join('pages','pages.id','section.page_id')
            ->select('content.*','pages.id as page_id')
            ->where('content.id',$id)->first();
        return view('dashboard_SEO.website_content_edit',compact('pages','section','content'));
    }

    public function update_content(Request $request){
        $content = DB::table('content')->where('section_id',$request->section_id)->where('id',$request->sequence_no)->first();
        if($content){
            DB::table('content')->where('section_id',$request->section_id)->where('id',$request->sequence_no)->update([
                // 'section_id'=>$request->section_id,
                // 'sequence_no'=>$request->sequence_no,
                'content'=>$request->content,
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }else{
            $count = DB::table('content')->where('section_id',$request->section_id)->count();
            DB::table('content')->insert([
                'section_id'=>$request->section_id,
                'sequence_no'=> ++$count,
                'content'=>$request->content,
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->route('pages_section_content');
    }

    public function update_image_content(Request $request){
        $image_content = DB::table('images_content')->where('section_id',$request->section_id)->first();
        if($request->hasFile('file')){
            $files = $request->file('file');
            $filename = \Storage::disk('s3')->put('medicine', $files);
        }else{
            $filename = $image_content->image;
        }
        if($image_content){
            DB::table('images_content')->where('section_id',$request->section_id)->update([
                'image'=>$filename,
                'alt'=>$request->alt,
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }else{
            DB::table('images_content')->insert([
                'section_id'=>$request->section_id,
                'image'=>$filename,
                'alt'=>$request->alt,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);

        }
        return redirect()->route('pages_image_content');
    }

    public function get_sections_by_page_id($id){
        $section = DB::table('section')->where('page_id',$id)->get();
        return response()->json($section);
    }

    public function get_sequences_by_section_id($id){
        $content = DB::table('content')->where('section_id',$id)->get();
        return response()->json($content);
    }

    public function get_content_by_content_id($id){
        $content = DB::table('content')->where('id',$id)->first();
        return response()->json($content);
    }

    public function get_image_content_by_section($id){
        $contents = DB::table('images_content')->where('section_id',$id)->first();
        if($contents->image != null){
            $contents->image = \App\Helper::check_bucket_files_url($contents->image);
        }
        return response()->json($contents);
    }

    public function save_pages(Request $request)
    {
        DB::table('pages')->insert([
            'name'=>$request->page_name,
            'url'=>$request->url,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('Page Added Successfully');
    }

    public function save_pages_section(Request $request)
    {
        DB::table('section')->insert([
            'page_id'=>$request->page_id,
            'section_name'=>$request->section_name,
            'sequence_no'=>$request->sequence_no,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('Page Section Added Successfully');
    }

    public function save_pages_section_content(Request $request)
    {
        DB::table('content')->insert([
            'section_id'=>$request->section_id,
            'sequence_no'=>$request->sequence_no,
            'content'=>$request->content,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('Page Section Added Successfully');
    }


    public function del_pages($id)
    {
        $data = DB::table('pages')->where('id',$id)->first();
        if($data!=null)
        {
            DB::table('pages')->where('url',$data->url)->delete();
        }
        return redirect()->back();
    }

    public function del_top_banner($id)
    {
        $data = DB::table('services')->where('id',$id)->first();
        if($data!=null)
        {
            DB::table('services')->where('id',$id)->delete();
        }
        return redirect()->back();
    }

    public function del_pages_section($id)
    {
        $data = DB::table('section')->where('id',$id)->first();
        if($data!=null)
        {
            DB::table('section')->where('id',$id)->delete();
        }
        return redirect()->back();
    }

    public function del_pages_section_content($id)
    {
        $data = DB::table('content')->where('id',$id)->first();
        if($data!=null)
        {
            DB::table('content')->where('id',$id)->delete();
        }
        return redirect()->back();
    }

    public function save_pages_meta_tag(Request $request)
    {
        $url = env('APP_URL');
        $url = $url.$request->url;
        DB::table('meta_tags')->insert([
            'url'=>$url,
            'name'=>'title',
            'content'=>$request->title,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        DB::table('meta_tags')->insert([
            'url'=>$url,
            'name'=>'description',
            'content'=>$request->description,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        DB::table('meta_tags')->insert([
            'url'=>$url,
            'name'=>'keywords',
            'content'=>$request->keywords,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('Tag Entered Successfully');
    }

    public function del_pages_meta_tag($id)
    {
        $data = DB::table('meta_tags')->where('id',$id)->first();
        if($data!=null)
        {
            DB::table('meta_tags')->where('url',$data->url)->delete();
        }
        return redirect()->back();
    }

    public function del_meta_tag($id)
    {
        $data = DB::table('meta_tags')->where('id',$id)->first();
        if($data!=null)
        {
            DB::table('meta_tags')->where('product_id',$data->product_id)->delete();
        }
        return redirect()->back();
    }

    public function edit_meta_tag(Request $request)
    {
        if($request->id != null)
        {
            DB::table('meta_tags')->where('id',$request->id)->update(['content'=>$request->content]);
        }
        return redirect()->back();
    }

}
