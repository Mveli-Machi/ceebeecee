<?php

namespace App\Http\Controllers\API;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontEndController extends Controller
{
    public function news(){

        $news = News::all();
        return response()->json([

            'status'=>200,
            'news'=>$news,

        ]);

    }
}