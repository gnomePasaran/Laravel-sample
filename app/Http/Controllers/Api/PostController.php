<?php

namespace App\Http\Controllers\Api;

use App\Facades\FileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Vote;
use Gate;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    const POSTS_PER_PAGE = 3;

    public function index(Post $postModel)
    {
        return response()->json(
            $postModel
                ->getPublishedPosts()
                ->paginate(self::POSTS_PER_PAGE)
        , 201);
    }

    public function show(Post $post)
    {
        return response()->json($post, 201);
    }

    public function store(PostRequest $request)
    {
        $setPost = $request->only('title', 'content', 'published');
        $setPost['user_id'] = Auth::user()->id;

        $post = Post::create($setPost); // returns array
        if ($request->file('file')) {
            $attach = FileUploader::storeFromHttp($request->file('file'));
            $post->attachments()->create($attach);
        }

        return response()->json($post, 201);
    }

    public function update(PostRequest $request, Post $post)
    {
        $setPost = $request->only('title', 'content', 'published');

        if ($post->update($setPost)) { // returns true/false
            if ($request->file('file')) {
                $attach = FileUploader::storeFromHttp($request->file('file'));
                $post->attachments()->create($attach);
            }

            return response()->json($post, 200);
        }

        return response()->json(null, 400);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }

}
