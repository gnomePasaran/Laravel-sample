<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class IndexController extends Controller
{
    const POSTS_PER_PAGE = 3;

    /**
     * @param Post $postModel
     *
     * @return Factory|View
     */
    public function index(Post $postModel)
    {
        $posts = $postModel->getPublishedPosts()->paginate(self::POSTS_PER_PAGE);

        return view('pages.home', ['posts' => $posts]);
    }
}
