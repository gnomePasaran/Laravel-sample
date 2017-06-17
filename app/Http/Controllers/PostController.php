<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'create', 'store', 'update', 'destroy']);
    }

    /**
    * Discription
    *
    * @return Response
    */
    public function index(Post $postModel)
    {
        $posts = $postModel->getPublishedPosts();
        return view('home', ['posts' => $posts]);
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
        $setPost = $request->only('title', 'content', 'published');
        $setPost['user_id'] = Auth::user()->id;

        Post::create($setPost); // returns array
        return redirect()->route('posts');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        if (Gate::denies('edit', $post)) {
          abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'title' => 'required|unique:posts|min:5|max:50',
          'content' => 'required',
        ]);
        $setPost = $request->only('title', 'content', 'published');
        $post = Post::find($id);

        if (Gate::denies('update', $post)) {
          abort(403, 'Unauthorized action.');
        }

        if ($post->save($setPost)) // returns true/false
          return redirect()->route('posts');
        else
          return view('posts.create', ['post' => $post]);
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
}
