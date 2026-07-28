<?php

namespace App\Mail;

use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public Rfq $rfq;

    public function __construct(Rfq $rfq)
    {
        $this->rfq = $rfq;
    }

    public function build()
    {
        return $this->subject('Surat Penawaran Harga Resmi PT. Prolabios Mitra Analitika [' . $this->rfq->rfq_number . ']')
                    ->view('emails.quotation-response');
    }
}
