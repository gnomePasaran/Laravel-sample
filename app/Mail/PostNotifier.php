<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Post;

class PostNotifier extends Mailable
{
    use Queueable, SerializesModels;

    private $post;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('laravel@laravel.com')
            ->view('emails.posts.notified', [
                'post' => $this->post,
            ])
            ->text('emails.posts.notified', [
                'post' => $this->post,
            ]);
    }
}
