<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WishGrantorConfirmation extends Mailable
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
            subject: 'SimplyWishes: You Accepted a Wish'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wish-grantor-confirmation',
            with: [
                'wish' => $this->wish,
                'creator' => $this->creator,
                'grantor' => $this->grantor,
                'wishUrl' => route('wishes.show', $this->wish->w_id),
                'inboxUrl' => route('inbox'),
                'loginUrl' => route('login'),
            ]
        );
    }
}
