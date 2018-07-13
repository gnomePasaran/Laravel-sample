<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Post;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => [
                'store',
                'update',
                'destroy',
            ]
        ]);
    }

    /**
     * @param Post $post
     * @param CommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePost(Post $post, CommentRequest $request)
    {
        $setComment = $request->only(['content']);
        $setComment['user_id'] = Auth::user()->id;
        $post->comments()->create($setComment);

        return redirect()->route('post.show', [
          'post' => $post,
        ]);
    }

    /**
     * @param Answer $answer
     * @param CommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAnswer(Answer $answer, CommentRequest $request)
    {
        $setComment = $request->only(['content']);
        $setComment['user_id'] = Auth::user()->id;
        $answer->comments()->create($setComment);

        return redirect()->route('post.show', [
          'post' => $answer->post,
        ]);
    }

    /**
     * @param Comment $comment
     * @param CommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Comment $comment, CommentRequest $request)
    {
        if (Gate::denies('edit', $comment)) {
            abort(403, 'Unauthorized action.');
        }

        $setComment = $request->only(['content']);
        $comment->update($setComment);

        $post = $comment->commentable instanceof Post
            ? $comment->commentable
            : $comment->commentable->post;

        return redirect()->route('post.show', [
          'post' => $post,
        ]);
    }

    /**
     * @param Comment $comment
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {
        if (Gate::denies('edit', $comment)) {
            abort(403, 'Unauthorized action.');
        }

        $post = $comment->commentable instanceof Post
            ? $comment->commentable
            : $comment->commentable->post;

        $comment->delete();

        return redirect()->route('post.show', [
          'post' => $post,
        ]);
    }
}
