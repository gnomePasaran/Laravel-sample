<?php

namespace App\Http\Controllers;

use App\Facades\FileUploader;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Vote;
use Gate;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        // only Authenticated user  app/Http/Kernel.php
        $this->middleware('auth', [
            'only' => [
                'create',
                'store',
                'update',
                'destroy',
                'subscribe',
                'voteUp',
                'voteDown',
                'voteCancel',
            ]
        ]);
    }

    public function show($slug, Post $postModel)
    {
        $post = $postModel->getPost($slug);

        return view('pages.posts.show', ['post' => $post]);
    }

    public function create()
    {
        $post = new Post;

        return view('pages.posts.create', ['post' => $post]);
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

        return redirect()->route('posts');
    }

    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if (Gate::denies('edit', $post)) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.posts.edit', [
            'post' => $post
        ]);
    }

    public function update(PostRequest $request, $id)
    {
        $setPost = $request->only('title', 'content', 'published');
        $post = Post::findOrFail($id);

        if (Gate::denies('update', $post)) {
            abort(403, 'Unauthorized action.');
        }

        if ($post->update($setPost)) { // returns true/false
            if ($request->file('file')) {
                $attach = FileUploader::storeFromHttp($request->file('file'));
                $post->attachments()->create($attach);
            }

            return redirect()->route('posts');
        }

        return view('pages.posts.create', ['post' => $post]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (Gate::denies('destroy', $post)) {
          abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts');
    }

    public function subscribe($id)
    {
        $post = Post::findOrFail($id);
        Auth::user()->subscribe($post);

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    public function voteUp($id)
    {
        $post = Post::findOrFail($id);
        $post->voteUp(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    public function voteDown($id)
    {
        $post = Post::findOrFail($id);
        $post->voteDown(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    public function voteCancel($id)
    {
        $post = Post::findOrFail($id);
        $post->voteCancel(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }
}
