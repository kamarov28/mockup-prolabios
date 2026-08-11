<?php

namespace App\Mail;

use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public Rfq $rfq;

    public function __construct(Rfq $rfq)
    {
        $this->rfq = $rfq;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('Surat Penawaran Harga Resmi PT. Prolabios Mitra Analitika [%s]', $this->rfq->rfq_number),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation-response',
        );
    }
}
