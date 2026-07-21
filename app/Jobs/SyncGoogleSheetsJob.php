<?php

namespace App\Jobs;

use App\Services\GoogleSheetsService;
use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncGoogleSheetsJob implements ShouldQueue
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
    public function handle(GoogleSheetsService $sheetsService): void
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

            Log::channel('contact')->info("Memulai perekaman data kontak ke Google Sheets (SyncGoogleSheetsJob).", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);

            $sheetsService->appendInquiry($data);

            Log::channel('contact')->info("Data kontak berhasil direkam ke Google Sheets secara asinkron.", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);
        } catch (\Exception $e) {
            Log::channel('contact')->warning('Gagal merekam data kontak ke Google Sheets lewat queue.', [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
                'exception_class' => get_class($e),
                'exception_code'  => $e->getCode(),
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
