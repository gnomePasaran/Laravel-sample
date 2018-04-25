<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    const POSTS_PER_PAGE = 3;

    public function index(Post $postModel)
    {
        $posts = $postModel->getPublishedPosts()->paginate(self::POSTS_PER_PAGE);

        return view('pages.home', ['posts' => $posts]);
    }
}
