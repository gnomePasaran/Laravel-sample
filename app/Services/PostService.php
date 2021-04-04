<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Auth;
use Illuminate\Validation\UnauthorizedException;

class PostService
{
    /** @var AttachmentService */
    private $attachmentService;

    /**
     * PostService constructor.
     *
     * @param AttachmentService $attachmentService
     */
    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * @param PostRequest $request
     * @param Post        $post
     *
     * @return Post
     *
     * @throws \Throwable
     */
    public function savePost(PostRequest $request, Post $post): Post
    {
        $attributes = $this->getParams($request);

        return $post->exists
            ? $this->updatePost($post, $attributes)
            : $this->createPost($post, $attributes);
    }

    /**
     * Get post params.
     *
     * @param PostRequest $request
     *
     * @return array
     */
    private function getParams(PostRequest $request): array
    {
        return $request->only('title', 'content', 'published', 'files');
    }

    /**
     * @param Post              $post
     * @param array             $attributes
     *
     * @return Post
     *
     * @throws \Throwable
     */
    private function createPost(Post $post, array $attributes): Post
    {
        throw_if(! Auth::check(), UnauthorizedException::class);

        $attributes['user_id'] = Auth::user()->id;

        $post = $post->create($attributes);

        if (isset($attributes['files']) && is_array($attributes['files'])) {
            $this->attachmentService->saveAttachments($post, $attributes['files']);
        }

        return $post;
    }

    /**
     * @param Post              $post
     * @param array             $attributes
     *
     * @return Post
     */
    private function updatePost(Post $post, array $attributes): Post
    {
        $post->update($attributes);

        $this->attachmentService->saveAttachments($post, $attributes['files'] ?? []);

        return $post;
    }
}
