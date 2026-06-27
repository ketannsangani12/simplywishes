<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WishGranted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Wish $wish,
        public User $creator,
        public User $grantor
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SimplyWishes: Your Wish Has Been Accepted'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wish-granted',
            with: [
                'wish' => $this->wish,
                'creator' => $this->creator,
                'grantor' => $this->grantor,
                'wishUrl' => route('wishes.show', $this->wish->w_id),
                'inboxUrl' => route('inbox'),
                'loginUrl' => route('login'),
                'postWishUrl' => route('wishes.create') . '#post_wish',
            ]
        );
    }
}
