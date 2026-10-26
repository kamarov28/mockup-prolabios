<?php

namespace App\Jobs;

use App\Mail\NewsletterBroadcastMail;
use App\Models\NewsletterBroadcast;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public NewsletterBroadcast $broadcast
    ) {}

    public function handle(): void
    {
        $query = NewsletterSubscriber::query()->where('is_active', true);

        if ($this->broadcast->target_sector_id) {
            $sectorId = $this->broadcast->target_sector_id;
            $query->whereHas('sectors', function ($q) use ($sectorId) {
                $q->where('sectors.id', $sectorId);
            });
        }

        $sectorName = $this->broadcast->targetSector?->name;
        $sentCount = 0;

        $query->chunk(50, function ($subscribers) use (&$sentCount, $sectorName) {
            foreach ($subscribers as $sub) {
                try {
                    Mail::to($sub->email)->send(new NewsletterBroadcastMail(
                        $sub,
                        $this->broadcast->subject,
                        $this->broadcast->content,
                        $sectorName,
                        $this->broadcast->type
                    ));
                    $sentCount++;
                } catch (\Throwable $e) {
                    Log::error("SendNewsletterBroadcastJob - Gagal mengirim email ke {$sub->email}: {$e->getMessage()}");
                }
            }
        });

        $this->broadcast->update([
            'recipients_count' => $sentCount,
            'sent_at' => now(),
        ]);
    }
}
