<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailSkeleton extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailSubject;
    public string $content;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $content)
    {
        $this->emailSubject = $subject;
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('gamligocharles2@gmail.com', 'COFINA-TOGO-CREDIT-DIGITAL'),
            cc: ['charles.gamligo@cofinacorp.com'],
            subject: "COFINA TOGO CREDIT DIGITAL - $this->emailSubject",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'EmailSkeleton',
            with: ['content' => $this->content, 'subject' => $this->emailSubject]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
