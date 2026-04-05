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

    /**
     * Create a new message instance.
     */
    public function __construct(Client $client, string $subjectLine = 'New Client Subscription - Support Needed', string $summary = 'A client needs support follow-up.')
    {
        $this->client = $client;
        $this->subjectLine = $subjectLine;
        $this->summary = $summary;
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
