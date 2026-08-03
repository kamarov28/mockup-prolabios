<?php

namespace App\Mail;

use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RfqCustomerReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Rfq $rfq;

    public function __construct(Rfq $rfq)
    {
        $this->rfq = $rfq;
    }

    public function build()
    {
        return $this->subject('[Prolabios] Tanda Terima Pengajuan Penawaran #' . $this->rfq->rfq_number)
                    ->view('emails.rfq-customer-receipt');
    }
}
