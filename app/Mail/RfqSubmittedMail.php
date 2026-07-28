<?php

namespace App\Mail;

use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RfqSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Rfq $rfq;

    public function __construct(Rfq $rfq)
    {
        $this->rfq = $rfq;
    }

    public function build()
    {
        return $this->subject('PROLABIOS - Pengajuan Penawaran Baru [' . $this->rfq->rfq_number . '] dari ' . $this->rfq->company_name)
                    ->view('emails.rfq-submitted');
    }
}
