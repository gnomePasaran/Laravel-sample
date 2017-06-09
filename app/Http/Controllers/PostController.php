<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
    * Discription
    *
    * @return Response
    */
    public function index(Post $postModel)
    {
        $posts = $postModel->getPublishedPosts();
        return view('posts.index', ['posts' => $posts]);
    }

    
}
