<?php

namespace App\Http\Controllers\Admin;

use App\Models\Galeri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class galeriController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $galeris = galeri::latest()->paginate(5);
        return view('admin.galeri.index', compact('galeris'));
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
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000'
        ]); 
 
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/galeris', $image->hashName());
 
        //save to DB
        $galeri = galeri::create([
            'image'  => $image->hashName()
        ]);
 
        if($galeri){
             //redirect dengan pesan sukses
             return redirect()->route('admin.galeri.index')->with(['success' => 'Data Berhasil Disimpan!']);
         }else{
             //redirect dengan pesan error
             return redirect()->route('admin.galeri.index')->with(['error' => 'Data Gagal Disimpan!']);
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
        $galeri = galeri::findOrFail($id);
        Storage::disk('local')->delete('public/galeris/'.basename($galeri->image));
        $galeri->delete();

        if($galeri){
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