<?php

namespace App\Jobs;

use App\Services\GoogleSheetsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncGoogleSheetsJob implements ShouldQueue
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
    public function handle(GoogleSheetsService $sheetsService): void
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

            Log::info("Memulai perekaman data kontak ke Google Sheets (SyncGoogleSheetsJob).", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);

            $sheetsService->appendInquiry($data);

            Log::info("Data kontak berhasil direkam ke Google Sheets secara asinkron.", [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
            ]);
        } catch (\Exception $e) {
            Log::warning('Google Sheets bermasalah atau belum dikonfigurasi.', [
                'correlation_id' => $correlationId,
                'inquiry_id' => $this->inquiryId,
                'exception_class' => get_class($e),
                'exception_code'  => $e->getCode(),
            ]);
            $this->fail(new \Exception("Google Sheets bermasalah atau belum dikonfigurasi."));
        }
    }
}
