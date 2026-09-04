<?php

namespace App\Jobs;

use App\Mail\ContactMail;
use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public int $timeout = 120;

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
            $inquiry = ContactInquiry::query()->find($this->inquiryId);
            if (! $inquiry) {
                Log::channel('contact')->warning('Data inquiry kontak tidak ditemukan untuk ID: '.$this->inquiryId);

                return;
            }

            $data = $inquiry->payload;
            if (! is_array($data) || empty($data)) {
                Log::channel('contact')->error('Data payload kontak terkorup atau kosong untuk ID: '.$this->inquiryId);
                throw new \Exception('Payload terkorup atau kosong.');
            }

            $correlationId = is_array($data) && isset($data['correlation_id']) ? (string) $data['correlation_id'] : 'unknown';

            Log::channel('contact')->info('Memulai pengiriman email kontak (SendContactEmailJob).', [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);

            $recipient = config('contact.to_address', 'marketing@prolabios.com');
            if (empty($recipient) || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Penerima email tidak valid atau belum dikonfigurasi.');
            }

            Mail::to($recipient)->send(new ContactMail($data));

            Log::channel('contact')->info('Email kontak berhasil dikirim secara asinkron.', [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);
        } catch (\Throwable $e) {
            Log::channel('contact')->error('Gagal mengirim email kontak lewat queue.', [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
                'exception_class' => get_class($e),
                'exception_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);

            // Differentiate configuration errors vs transient failures
            if (str_contains($e->getMessage(), 'belum dikonfigurasi') || str_contains($e->getMessage(), 'Payload terkorup')) {
                $this->fail($e);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::channel('contact')->critical('Contact email job failed after all retries', [
            'inquiry_id' => $this->inquiryId,
            'error' => $exception->getMessage(),
        ]);
    }
}
