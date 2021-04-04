<?php

namespace Tests\Unit\Services\AttachmentService;

use App\Models\Post;
use App\Services\AttachmentService;
use Tests\TestCase;

class AttachmentServiceTest extends TestCase
{
    /**
     * Method returns post.
     */
    public function testReturnsPost()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        $attachmentService = new AttachmentService();

        $this->assertEquals(
            $post,
            $attachmentService->saveAttachments($post, [])
        );
    }
}
