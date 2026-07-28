<?php

namespace App\Jobs;

use App\Mail\QuotationResponseMail;
use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendQuotationResponseEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = [10, 30, 60];

    protected int $rfqId;
    protected string $customerEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(int $rfqId, ?string $customerEmail = null)
    {
        $this->rfqId = $rfqId;
        $this->customerEmail = $customerEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $rfq = Rfq::find($this->rfqId);
            
            if (!$rfq) {
                Log::warning("RFQ not found for quotation email. ID: {$this->rfqId}");
                return;
            }

            $recipient = $this->customerEmail ?? $rfq->email;
            
            if (empty($recipient) || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                Log::error("Invalid customer email for RFQ quotation", [
                    'rfq_id' => $this->rfqId,
                    'email' => $recipient,
                ]);
                return;
            }

            Log::info("Sending quotation response email", [
                'rfq_number' => $rfq->rfq_number,
                'customer_email' => $recipient,
            ]);

            Mail::to($recipient)->send(new QuotationResponseMail($rfq));

            Log::info("Quotation response email sent successfully", [
                'rfq_number' => $rfq->rfq_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send quotation response email', [
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
        Log::critical('Quotation email job failed after all retries', [
            'rfq_id' => $this->rfqId,
            'error' => $exception->getMessage(),
        ]);
    }
}
