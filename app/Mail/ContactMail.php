<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = sprintf('New Inquiry from Website: %s', $this->data['subjek_label'] ?? 'General Inquiry');

        // Validate email before using it as reply-to to avoid header injection
        $replyTo = $this->data['email'] ?? null;
        if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            return new Envelope(
                subject: $subject,
                replyTo: [new Address($replyTo, $this->data['nama'] ?? null)]
            );
        }

        // Fallback to a generic no-reply address
        return new Envelope(
            subject: $subject,
            replyTo: [new Address('no-reply@'.request()->getHost())]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
