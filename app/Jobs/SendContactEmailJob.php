<?php

namespace App\Jobs;

use App\Mail\ContactMail;
use App\Models\ContactInquiry;
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

    public int $tries = 3;

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
            $inquiry = ContactInquiry::find($this->inquiryId);
            if (!$inquiry) {
                Log::channel('contact')->warning("Data inquiry kontak tidak ditemukan untuk ID: " . $this->inquiryId);
                return;
            }

            $data = $inquiry->payload;
            if (empty($data)) {
                Log::channel('contact')->error("Data payload kontak terkorup atau kosong untuk ID: " . $this->inquiryId);
                throw new \Exception("Payload terkorup atau kosong.");
            }

            $correlationId = $data['correlation_id'] ?? 'unknown';

            Log::channel('contact')->info("Memulai pengiriman email kontak (SendContactEmailJob).", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);

            $recipient = config('contact.to_address', 'marketing@prolabios.com');
            if (empty($recipient) || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("Penerima email tidak valid atau belum dikonfigurasi.");
            }

            Mail::to($recipient)->send(new ContactMail($data));

            Log::channel('contact')->info("Email kontak berhasil dikirim secara asinkron.", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);
        } catch (\Exception $e) {
            Log::channel('contact')->error('Gagal mengirim email kontak lewat queue.', [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
                'exception_class' => get_class($e),
                'exception_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);

            // Differentiate configuration errors vs transient failures
            if (str_contains($e->getMessage(), 'belum dikonfigurasi') || str_contains($e->getMessage(), 'Payload terkorup')) {
                $this->fail(new \Exception($e->getMessage(), $e->getCode()));
            } else {
                throw new \Exception($e->getMessage(), $e->getCode());
            }
        }
    }
}
