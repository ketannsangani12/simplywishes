<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationAcceptedNonFinancial extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
        public User $acceptor,
        public User $donor
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SimplyWishes: You Accepted a Donation'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-accepted-non-financial',
            with: [
                'donation' => $this->donation,
                'acceptor' => $this->acceptor,
                'donor' => $this->donor,
                'donationUrl' => route('donations.show', $this->donation->id),
                'inboxUrl' => route('inbox'),
                'loginUrl' => route('login'),
            ]
        );
    }
}
