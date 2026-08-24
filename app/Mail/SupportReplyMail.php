<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $clientName,
        public string $clientSubject,
        public string $replyContent,
        public string $originalMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . $this->clientSubject . ' — Boutique Paris',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.support-reply');
    }
}