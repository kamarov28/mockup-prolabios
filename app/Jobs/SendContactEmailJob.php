<?php

namespace App\Jobs;

use App\Mail\ContactMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendContactEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $inquiryId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $inquiryId)
    {
        $this->inquiryId = $inquiryId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $correlationId = 'unknown';
        try {
            $inquiry = \App\Models\ContactInquiry::find($this->inquiryId);
            if (!$inquiry) {
                Log::warning("Data inquiry kontak tidak ditemukan untuk ID: " . $this->inquiryId);
                return;
            }

            $data = $inquiry->payload;
            $correlationId = $data['correlation_id'] ?? 'unknown';

            Log::info("Memulai pengiriman email kontak (SendContactEmailJob).", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);

            $recipient = config('contact.to_address', 'marketing@prolabios.com');
            Mail::to($recipient)->send(new ContactMail($data));

            Log::info("Email kontak berhasil dikirim secara asinkron.", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email kontak lewat queue.', [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
                'exception_class' => get_class($e),
                'exception_code' => $e->getCode(),
            ]);
            $this->fail(new \Exception("Gagal mengirim email kontak lewat queue."));
        }
    }
}
