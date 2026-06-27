<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationAcceptedCreator extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
        public User $donor,
        public User $acceptor
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SimplyWishes: Your Donation Has Been Accepted'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-accepted-creator',
            with: [
                'donation' => $this->donation,
                'donor' => $this->donor,
                'acceptor' => $this->acceptor,
                'donationUrl' => route('donations.show', $this->donation->id),
                'completeUrl' => route('donations.show', $this->donation->id),
                'createDonationUrl' => route('donations.create'),
                'inboxUrl' => route('inbox'),
                'loginUrl' => route('login'),
            ]
        );
    }
}
