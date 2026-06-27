<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationCreatorCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
        public User $donor
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SimplyWishes: Your Donation Has Been Fulfilled'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-creator-completed',
            with: [
                'donation' => $this->donation,
                'donor' => $this->donor,
                'donationUrl' => route('donations.show', $this->donation->id),
                'happyStoriesUrl' => route('happy.stories'),
                'forumUrl' => route('forum'),
                'loginUrl' => route('login'),
            ]
        );
    }
}
