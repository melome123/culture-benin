<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Contenu;

class ContenuRejete extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contenu $contenu, public ?string $reason = null)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre contenu a été rejeté',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contenu-rejete',
            with: [
                'contenu' => $this->contenu,
                'user' => $this->contenu->user,
                'reason' => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
