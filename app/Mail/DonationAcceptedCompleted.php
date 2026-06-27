<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationAcceptedCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
        public User $acceptor
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SimplyWishes: Your Accepted Donation Has Been Fulfilled'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-accepted-completed',
            with: [
                'donation' => $this->donation,
                'acceptor' => $this->acceptor,
                'donationUrl' => route('donations.show', $this->donation->id),
                'happyStoriesUrl' => route('happy.stories'),
                'forumUrl' => route('forum'),
                'loginUrl' => route('login'),
            ]
        );
    }
}
