<?php

namespace App\Http\Controllers;

use App\Facades\FileUploader;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\PostService;
use Gate;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    /** @var PostService  */
    private $postService;

    /**
     * PostController constructor.
     *
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;

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
     * @param $slug
     * @param Post $postModel
     *
     * @return Factory|View
     */
    public function show($slug, Post $postModel)
    {
        $post = $postModel->getPost($slug);

        return view('pages.posts.show', ['post' => $post]);
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $post = new Post;

        return view('pages.posts.create', ['post' => $post]);
    }

    /**
     * @param PostRequest $request
     *
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(PostRequest $request)
    {
        $this->postService->savePost($request, new Post());

        return redirect()->route('posts');
    }

    /**
     * @param string $slug
     *
     * @return Factory|View
     */
    public function edit(string $slug)
    {
        $post = Post::query()->where('slug', $slug)->firstOrFail();

        if (Gate::denies('edit', $post)) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.posts.edit', [
            'post' => $post
        ]);
    }

    /**
     * @param PostRequest $request
     * @param int         $id
     *
     * @return Factory|RedirectResponse|View
     *
     * @throws \Throwable
     */
    public function update(PostRequest $request, int $id)
    {
        /** @var Post $post */
        $post = Post::findOrFail($id);

        if (Gate::denies('update', $post)) {
            abort(403, 'Unauthorized action.');
        }

        if ($this->postService->savePost($request, $post)) { // returns Post
            return redirect()->route('posts');
        }

        return view('pages.posts.create', ['post' => $post]);
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);

        if (Gate::denies('destroy', $post)) {
          abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts');
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function subscribe(int $id)
    {
        /** @var Post $post */
        $post = Post::findOrFail($id);
        Auth::user()->subscribe($post);

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function voteUp(int $id)
    {
        /** @var Post $post */
        $post = Post::findOrFail($id);
        $post->voteUp(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function voteDown(int $id)
    {
        /** @var Post $post */
        $post = Post::findOrFail($id);
        $post->voteDown(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function voteCancel(int $id)
    {
        /** @var Post $post */
        $post = Post::findOrFail($id);
        $post->voteCancel(Auth::user());

        return redirect()->route('post.show', [
            'id' => $id
        ]);
    }
}
