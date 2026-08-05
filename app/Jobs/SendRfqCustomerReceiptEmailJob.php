<?php

namespace App\Jobs;

use App\Mail\RfqCustomerReceiptMail;
use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRfqCustomerReceiptEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public array $backoff = [10, 30, 60];

    protected int $rfqId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $rfqId)
    {
        $this->rfqId = $rfqId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $rfq = Rfq::find($this->rfqId);

            if (!$rfq) {
                Log::warning("RFQ not found for customer receipt email. ID: {$this->rfqId}");
                return;
            }

            if (empty($rfq->email) || !filter_var($rfq->email, FILTER_VALIDATE_EMAIL)) {
                Log::error("Invalid customer email address for RFQ receipt", [
                    'rfq_id' => $this->rfqId,
                    'email' => $rfq->email,
                ]);
                return;
            }

            Log::info("Sending RFQ customer receipt email", [
                'rfq_number' => $rfq->rfq_number,
                'customer_email' => $rfq->email,
            ]);

            Mail::to($rfq->email)->send(new RfqCustomerReceiptMail($rfq));

            Log::info("RFQ customer receipt email sent successfully", [
                'rfq_number' => $rfq->rfq_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send RFQ customer receipt email', [
                'rfq_id' => $this->rfqId,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            throw $e; // Let queue handle retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical('RFQ customer receipt email job failed after all retries', [
            'rfq_id' => $this->rfqId,
            'error' => $exception->getMessage(),
        ]);
    }
}
