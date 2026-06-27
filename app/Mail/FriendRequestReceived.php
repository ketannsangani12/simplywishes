<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FriendRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $sender,
        public User $receiver
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have a new Friend!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.friend-request-received',
            with: [
                'sender' => $this->sender,
                'receiver' => $this->receiver,
                'loginUrl' => route('login'),
            ]
        );
    }
}
