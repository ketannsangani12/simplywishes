<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
        public User $user
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanks for Offering a Donation!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-created',
            with: [
                'donation' => $this->donation,
                'user' => $this->user,
                'appUrl' => config('app.url'),
            ]
        );
    }
}
