<?php

namespace App\Jobs;

use App\Mail\RfqApprovedMail;
use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRfqApprovedEmailJob implements ShouldQueue
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

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    protected int $rfqId;
    protected string $adminEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(int $rfqId, ?string $adminEmail = null)
    {
        $this->rfqId = $rfqId;
        $this->adminEmail = $adminEmail ?? config('contact.to_address') ?? config('mail.from.address', 'marketing@prolabios.com');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $rfq = Rfq::query()->with('items')->find($this->rfqId);

            if (!$rfq) {
                Log::warning("RFQ not found for approval notification. ID: {$this->rfqId}");
                return;
            }

            Log::info("Sending RFQ approved email", [
                'rfq_number' => $rfq->rfq_number,
                'company_name' => $rfq->company_name,
                'admin_email' => $this->adminEmail,
            ]);

            Mail::to($this->adminEmail)->send(new RfqApprovedMail($rfq));

            Log::info("RFQ approved email sent successfully", [
                'rfq_number' => $rfq->rfq_number,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to send RFQ approved email', [
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
        Log::critical('RFQ approved email job failed after all retries', [
            'rfq_id' => $this->rfqId,
            'error' => $exception->getMessage(),
        ]);
    }
}
