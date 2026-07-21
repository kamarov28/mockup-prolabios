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

    // Store encrypted string payload to prevent PII exposure in the queue database table
    protected string $encryptedData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->encryptedData = \Illuminate\Support\Facades\Crypt::encrypt($data);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $data = \Illuminate\Support\Facades\Crypt::decrypt($this->encryptedData);
            $recipient = config('contact.to_address', 'marketing@prolabios.com');

            Mail::to($recipient)->send(new ContactMail($data));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email kontak lewat queue: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e; // Rethrow to let the queue manager handle retries/failures
        }
    }
}
