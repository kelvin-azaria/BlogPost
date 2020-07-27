<?php

namespace App\Mail;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Comment was posted on your {$this->comment->commentable->title} blog post";
        // $path = (storage_path('app/public').'/'.$this->comment->user->image->path);
        return $this
            // ->attach($path, [
            //     'as' => 'profile.png',
            //     'mime' => 'image/png'
            // ])
            
            // ->attachFromStorage($this->comment->user->image->path, 'profile.png')
            
            // ->attachFromStorageDisk('public', $this->comment->user->image->path)
            ->attachData(Storage::get($this->comment->user->image->path), 'profile.png',[
                'mime' => 'image/png'
            ])
            ->subject($subject)
            ->view('emails.posts.commented');
    }
}
