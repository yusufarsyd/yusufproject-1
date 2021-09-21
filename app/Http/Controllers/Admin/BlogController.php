<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $blogs = Blog::latest()->when(request()->q, function($blogs) {
            $blogs = $blogs->where('title', 'like', '%'. request()->q . '%');
        })->paginate(10);

        return view('admin.blog.index', compact('blogs'));
    }
    

      /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('admin.blog.create');
    }
    
    
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image'             => 'required|image|mimes:png,jpg,jpeg',
            'title'             => 'required',
            'tags'              => 'required',
            'content'             => 'required'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/blogs', $image->hashName());

        $blog = Blog::create([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title, '-'),
            'tags'                => $request->tags,
            'content'              => $request->content,
            'image'             => $image->hashName()
        ]);

        if($blog){
            //redirect dengan pesan sukses
            return redirect()->route('admin.blog.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.blog.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }
    
    /**
     * edit
     *
     * @param  mixed $blog
     * @return void
     */
    public function edit(Blog $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request, Blog $blog)
    {
        $this->validate($request, [
            'image'              => 'required|image|mimes:png,jpg,jpeg',
            'title'              => 'required',
            'tags'                 => 'required',
            'content'             => 'required'
        ]); 

        //check jika image kosong
        if($request->file('image') == '') {
            
            //update data tanpa image
            $blog = Blog::findOrFail($blog->id);
            $blog->update([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title, '-'),
            'tags'                => $request->tags,
            'content'              => $request->content
            ]);

        } else {

            //hapus image lama
            Storage::disk('local')->delete('public/blogs/'.basename($blog->image));

            //upload image baru
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());

            //update dengan image baru
            $blog = Blog::findOrFail($blog->id);
            $blog->update([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title, '-'),
            'tags'              => $request->tags,
            'content'           => $request->content,
            'image'             => $image->hashName()
            ]);
        }

        if($blog){
            //redirect dengan pesan sukses
            return redirect()->route('admin.blog.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.blog.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }
    
    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        Storage::disk('local')->delete('public/blogs/'.basename($blog->image));
        $blog->delete();

        if($blog){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}