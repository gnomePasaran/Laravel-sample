<?php

namespace App\Http\Controllers;

use App\Facades\FileUploader;
use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use App\Models\Post;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => 'store', 'update', 'destroy',
            'toggle_best', 'voteUp', 'voteDown', 'voteCancel'
        ]);
    }

    public function store(AnswerRequest $request, $postId)
    {
        $setAnswer = $request->only(['content']);
        $setAnswer['user_id'] = Auth::user()->id;

        $post = Post::findOrFail($postId);
        $answer = $post->answers()->create($setAnswer);

        if ($request->file('file')) {
            $attach = FileUploader::storeFromHttp($request->file('file'));
            $answer->attachments()->create($attach);
        }

        return redirect()->route('post.show', [
          'id' => $post->id
        ]);
    }

    public function update(AnswerRequest $request, $postId, $id)
    {
        $setAnswer = $request->only('content');
        $answer = Answer::findOrFail($id);

        if (Gate::denies('update', $answer)) {
            abort(403, 'Unauthorized action.');
        }

        $answer->updateAnswer($setAnswer);
        if ($request->file('file')) {
            $attach = FileUploader::storeFromHttp($request->file('file'));
            $answer->attachments()->create($attach);
        }

        return redirect()->route('post.show', ['id' => $postId]);
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

    public function toggleBest($id)
    {
        $answer = Answer::findOrFail($id);

        if (Gate::denies('toggleBest', $answer)) {
            abort(403, 'Unauthorized action.');
        }

        $answer->toggle_best();

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }

    public function voteUp($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->voteUp(Auth::user());

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }

    public function voteDown($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->voteDown(Auth::user());

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }

    public function voteCancel($id)
    {

        $answer = Answer::findOrFail($id);
        $answer->voteCancel(Auth::user());

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }
}
