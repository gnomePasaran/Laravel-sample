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

    public function show($id)
    {
        $post = Post::find($id);
        $post->with('answers')->get();
        return view('posts.show', ['post' => $post]);
    }

    public function create()
    {
        $post = new Post;
        return view('posts.create', ['post' => $post]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'title' => 'required|unique:posts|min:5|max:50',
          'content' => 'required'
        ]);
        $setPost = $request->only('title', 'content');

        Post::create($setPost); // returns array
        return redirect()->route('posts');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'title' => 'required|unique:posts|min:5|max:50',
          'content' => 'required'
        ]);
        $setPost = $request->only('title', 'content', 'published');
        $post = Post::find($id);

        if ($post->save($setPost)) // returns true/false
          return redirect()->route('posts');
        else
          return view('posts.create', ['post' => $post]);
    }
}
