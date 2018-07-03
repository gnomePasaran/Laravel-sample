<?php

namespace App\Http\Controllers;

use App\Facades\FileUploader;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Vote;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    const POSTS_PER_PAGE = 3;

    /**
     * PostController constructor.
     */
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

    /**
     * @param Post $postModel
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Post $postModel)
    {
        $posts = $postModel->getPublishedPosts()->paginate(self::POSTS_PER_PAGE);

        return view('pages.home', ['posts' => $posts]);
    }

    /**
     * @param $id
     * @param Post $postModel
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, Post $postModel)
    {
        $post = $postModel->getPost($id);

        return view('pages.posts.show', ['post' => $post]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $post = new Post;

        return view('pages.posts.create', ['post' => $post]);
    }

    /**
     * @param PostRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if (Gate::denies('edit', $post)) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.posts.edit', [
            'post' => $post
        ]);
    }

    /**
     * @param PostRequest $request
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
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

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (Gate::denies('destroy', $post)) {
          abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe($id)
    {
        $post = Post::findOrFail($id);
        Auth::user()->subscribe($post);

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voteUp($id)
    {
        $post = Post::findOrFail($id);
        $post->voteUp(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voteDown($id)
    {
        $post = Post::findOrFail($id);
        $post->voteDown(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voteCancel($id)
    {
        $post = Post::findOrFail($id);
        $post->voteCancel(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }
}
