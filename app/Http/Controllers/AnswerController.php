<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
use App\Models\Post;
use App\Http\Requests\AnswerRequest;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'store', 'update', 'destroy']);
    }

    public function store(AnswerRequest $request, $postId)
    {
        $setAnswer = $request->only('content');
        $setAnswer['user_id'] = Auth::user()->id;

        $post = Post::findOrFail($postId);
        $post->answers()->create($setAnswer);

        return redirect()->route('post.show', ['id' => $post->id]);
    }

    public function update(AnswerRequest $request, $postId, $id)
    {
        $setAnswer = $request->only('content');
        $post = Post::findOrFail($postId);

        if (Gate::denies('update', $answer)) {
          abort(403, 'Unauthorized action.');
        }

        $post->answers()->update($setAnswer);

        return redirect()->route('post.show', ['id' => $post->id]);
    }

    public function destroy($postId, $id)
    {
        $answer = Answer::findOrFail($id);

        if (Gate::denies('destroy', $answer)) {
          abort(403, 'Unauthorized action.');
        }

        $answer->delete();

        return redirect()->route('post.show', ['id' => $postId]);
    }
}
