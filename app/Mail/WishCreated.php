<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WishCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Wish $wish,
        public User $user
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Wish Has Been Created Successfully'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wish-created',
            with: [
                'wish' => $this->wish,
                'user' => $this->user,
                'appUrl' => config('app.url'),
            ]
        );
    }
}
