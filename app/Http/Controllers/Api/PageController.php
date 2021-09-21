<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data Page
         $pages = Page::latest()->get();


        //return with response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Page',
            'data'    => $pages,
        ], 200);
    }
    
}