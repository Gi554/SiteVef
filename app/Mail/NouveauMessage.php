<?php

namespace App\Mail;

use App\Models\Messages;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouveauMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Messages $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->contactMessage->email],
            subject: '[VEF] Nouveau message : ' . $this->contactMessage->objet,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nouveau_message',
            text: 'emails.text.nouveau_message',
        );
    }
}
