<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
    * Discription
    *
    * @return Response
    */
    public function index()
    {
        return view('posts.index');
    }
}
