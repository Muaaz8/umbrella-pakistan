<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::orderBy('id','desc')->paginate(10);
        return view('dashboard_admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard_admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->content = $request->content;
        $blog->meta_title = $request->meta_title;
        $blog->status = 1;
        if ($request->hasFile('featured_image')) {
            $files = $request->file('featured_image');
            $filename = \Storage::disk('s3')->put('blogs', $files);
            $blog->featured_image = $filename;
        }
        if ($request->has('meta_name') && $request->has('meta_content')) {
            $meta_tags = $request->input('meta_name');
            $meta_content = $request->input('meta_content');
            $meta_tags = array_map('trim', $meta_tags);
            $meta_content = array_map('trim', $meta_content);
            $meta_tags = array_filter($meta_tags);
            $meta_content = array_filter($meta_content);
            $meta_tags = array_values($meta_tags);
            $meta_content = array_values($meta_content);
            $meta_tags = array_combine($meta_tags, $meta_content);
            $blog->meta_tags = json_encode($meta_tags);
        }
        $blog->save();
        return redirect()->route('admin_blog.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('dashboard_admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->route('admin_blog.index')->with('error', 'Blog not found.');
        }
        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->content = $request->content;
        $blog->status = 1;
        if ($request->hasFile('featured_image')) {
            $files = $request->file('featured_image');
            $filename = \Storage::disk('s3')->put('blogs', $files);
            $blog->featured_image = $filename;
        }
        if ($request->has('meta_name') && $request->has('meta_content')) {
            $meta_tags = $request->input('meta_name');
            $meta_content = $request->input('meta_content');

            $meta_tags = array_map('trim', $meta_tags);
            $meta_content = array_map('trim', $meta_content);

            $meta_tags = array_filter($meta_tags);
            $meta_content = array_filter($meta_content);

            $meta_tags = array_values($meta_tags);
            $meta_content = array_values($meta_content);

            $meta_tags = array_combine($meta_tags, $meta_content);
            $blog->meta_tags = json_encode($meta_tags);
        }
        $blog->save();
        return redirect()->route('admin_blog.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if ($blog) {
            $blog->delete();
            return redirect()->route('admin_blog.index')->with('success', 'Blog deleted successfully.');
        } else {
            return redirect()->route('admin_blog.index')->with('error', 'Blog not found.');
        }

    }

    public function status($id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            if($blog->status == 1){
                $blog->status = 0;
            }else{
                $blog->status = 1;
            }
            $blog->save();
            return redirect()->route('admin_blog.index')->with('success', 'Blog status updated to active.');
        } else {
            return redirect()->route('admin_blog.index')->with('error', 'Blog not found.');
        }
    }

    public function blog_index()
    {
        $featured_blogs = Blog::where('status', 1)->orderBy('id','desc')->first();
        $blogs = Blog::where('status', 1)->where('id', '!=', optional($featured_blogs)->id)->orderBy('id', 'desc')->paginate(10);
        return view('blogs.main', compact('blogs','featured_blogs'));
    }

    public function blog_single($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (!$blog) {
            return redirect()->route('blog_index')->with('error', 'Blog not found.');
        }
        return view('blogs.single', compact('blog'));
    }
}
