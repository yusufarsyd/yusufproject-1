<?php

namespace App\Http\Controllers\Api;

use App\Models\Galeri;
use App\Http\Controllers\Controller;

class GaleriController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data galeris
        $galeris = Galeri::latest()->get();

        //return with response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Galeri',
            'data'    => $galeris,
        ], 200);
    }
}