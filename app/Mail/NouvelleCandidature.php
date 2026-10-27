<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvelleCandidature extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Candidature $candidature) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->candidature->candidature_email],
            subject: '[VEF] Nouvelle candidature : ' . $this->candidature->candidature_prenom . ' ' . $this->candidature->candidature_nom,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nouvelle_candidature',
            text: 'emails.text.nouvelle_candidature',
        );
    }
}
