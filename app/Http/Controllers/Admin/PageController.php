<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $pages = Page::latest()->when(request()->q, function($pages) {
            $pages = $pages->where('title', 'like', '%'. request()->q . '%');
        })->paginate(10);

        return view('admin.page.index', compact('pages'));
    }
    

      /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('admin.page.create');
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
            'title'             => 'required',
            'content'             => 'required'
        ]);

        $page = page::create([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title, '-'),
            'content'              => $request->content
        ]);

        if($page){
            //redirect dengan pesan sukses
            return redirect()->route('admin.page.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.page.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }
    
    /**
     * edit
     *
     * @param  mixed $page
     * @return void
     */
    public function edit(Page $page)
    {
        return view('admin.page.edit', compact('page'));
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $page
     * @return void
     */
    public function update(Request $request, Page $page)
    {
        $this->validate($request, [
            'title'              => 'required',
            'content'             => 'required'
        ]); 

        

        if($page){
            //redirect dengan pesan sukses
            return redirect()->route('admin.page.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.page.index')->with(['error' => 'Data Gagal Diupdate!']);
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
        $page = Page::findOrFail($id);
        $page->delete();

        if($page){
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