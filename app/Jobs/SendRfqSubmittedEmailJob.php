<?php

namespace App\Jobs;

use App\Mail\RfqSubmittedMail;
use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRfqSubmittedEmailJob implements ShouldQueue
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
    protected string $adminEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(int $rfqId, ?string $adminEmail = null)
    {
        $this->rfqId = $rfqId;
        $this->adminEmail = $adminEmail ?? config('mail.from.address', 'sales@prolabios.com');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $rfq = Rfq::find($this->rfqId);
            
            if (!$rfq) {
                Log::warning("RFQ not found for email notification. ID: {$this->rfqId}");
                return;
            }

            Log::info("Sending RFQ submission email", [
                'rfq_number' => $rfq->rfq_number,
                'company_name' => $rfq->company_name,
                'admin_email' => $this->adminEmail,
            ]);

            Mail::to($this->adminEmail)->send(new RfqSubmittedMail($rfq));

            Log::info("RFQ submission email sent successfully", [
                'rfq_number' => $rfq->rfq_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send RFQ submission email', [
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
        Log::critical('RFQ email job failed after all retries', [
            'rfq_id' => $this->rfqId,
            'error' => $exception->getMessage(),
        ]);
    }
}
