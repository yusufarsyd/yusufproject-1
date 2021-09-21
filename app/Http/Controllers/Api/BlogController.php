<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;

/**
 * BlogController
 */
class BlogController extends Controller
{
        
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(6);
        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Blogs"
            ],
            "data" => $blogs
        ], 200);
    }
   
    /**
     * show
     *
     * @param  mixed $slug
     * @return void
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if($blog) {

            return response()->json([
                "response" => [
                    "status"    => 200,
                    "message"   => "Detail Data Blog"
                ],
                "data" => $blog
            ], 200);

        } else {

            return response()->json([
                "response" => [
                    "status"    => 404,
                    "message"   => "Data Blog Tidak Ditemukan!"
                ],
                "data" => null
            ], 404);

        }
    }

    
    /**
     * BlogHomePage
     *
     * @return void
     */
    public function BlogHomePage()
    {
        $blogs = Blog::latest()->take(6)->get();
        return response()->json([
            "response" => [
                "status"    => 200,
                "message"   => "List Data Blogs Homepage"
            ],
            "data" => $blogs
        ], 200);
    }
}