<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Post;

class AnswerController extends Controller
{
    public function store(Request $request, $postId)
    {
        $this->validate($request, [
            'content' => 'required|min:3'
        ]);
        $setAnswer = $request->only('content');
        $post = Post::findOrFail($postId);
        $post->answers()->create($setAnswer);

        return redirect()->route('post.show', ['id' => $post->id]);
    }

    public function update(Request $request, $postId, $id)
    {
    }
}
