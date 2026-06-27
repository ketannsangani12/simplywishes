<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WishGrantorFulfilled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Wish $wish,
        public User $grantor
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SimplyWishes: The Wish You Granted Is Now Granted'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wish-grantor-fulfilled',
            with: [
                'wish' => $this->wish,
                'grantor' => $this->grantor,
                'wishUrl' => route('wishes.show', $this->wish->w_id),
                'happyStoriesUrl' => route('happy.stories'),
                'forumUrl' => route('forum'),
            ]
        );
    }
}
