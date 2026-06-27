<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WishFulfilled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Wish $wish,
        public User $creator
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SimplyWishes: Your Wish Has Been Fulfilled'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wish-fulfilled',
            with: [
                'wish' => $this->wish,
                'creator' => $this->creator,
                'wishUrl' => route('wishes.show', $this->wish->w_id),
                'happyStoriesUrl' => route('happy.stories'),
            ]
        );
    }
}
