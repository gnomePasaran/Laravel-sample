<?php

namespace App\Http\Controllers;

use App\Facades\FileUploader;
use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use App\Models\Post;
use Gate;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * AnswerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => [
                'store',
                'update',
                'destroy',
                'toggleBest',
                'voteUp',
                'voteDown',
                'voteCancel',
            ]
        ]);
    }

    /**
     * @param AnswerRequest $request
     * @param $postId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * @param AnswerRequest $request
     * @param $postId
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AnswerRequest $request, $postId, $id)
    {
        $setAnswer = $request->only('content');
        $answer = Answer::findOrFail($id);

        if (Gate::denies('update', $answer)) {
            abort(403, 'Unauthorized action.');
        }

        $answer->update($setAnswer);
        if ($request->file('file')) {
            $attach = FileUploader::storeFromHttp($request->file('file'));
            $answer->attachments()->create($attach);
        }

        return redirect()->route('post.show', ['id' => $postId]);
    }

    /**
     * @param $postId
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy($postId, $id)
    {
        $answer = Answer::findOrFail($id);

        if (Gate::denies('destroy', $answer)) {
            abort(403, 'Unauthorized action.');
        }

        $answer->delete();

        return redirect()->route('post.show', ['id' => $postId]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleBest($id)
    {
        $answer = Answer::findOrFail($id);

        if (Gate::denies('toggleBest', $answer)) {
            abort(403, 'Unauthorized action.');
        }

        $answer->toggleBest();

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voteUp($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->voteUp(Auth::user());

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voteDown($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->voteDown(Auth::user());

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voteCancel($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->voteCancel(Auth::user());

        return redirect()->route('post.show', ['id' => $answer->post_id]);
    }
}
