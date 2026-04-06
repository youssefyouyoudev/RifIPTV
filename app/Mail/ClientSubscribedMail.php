<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientSubscribedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Client $client;
    public string $subjectLine;
    public string $summary;
    public string $eyebrow;
    public array $details;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Client $client,
        string $subjectLine = 'New client checkout submitted',
        string $summary = 'A client needs support follow-up.',
        string $eyebrow = 'Operational alert',
        array $details = []
    )
    {
        $this->client = $client;
        $this->subjectLine = $subjectLine;
        $this->summary = $summary;
        $this->eyebrow = $eyebrow;
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.client_subscribed',
            with: [
                'client' => $this->client,
                'subjectLine' => $this->subjectLine,
                'summary' => $this->summary,
                'eyebrow' => $this->eyebrow,
                'details' => $this->details,
            ],
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
