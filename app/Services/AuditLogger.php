<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Log a sensitive or critical administrative action.
     *
     * @param string $action
     * @param string|null $targetType
     * @param int|string|null $targetId
     * @param array $details
     * @return void
     */
    public static function log(string $action, ?string $targetType = null, $targetId = null, array $details = []): void
    {
        $user = Auth::user();

        $payload = [
            'action' => $action,
            'actor_id' => $user?->id ?? null,
            'actor_name' => $user?->name ?? 'Guest/Anonymous',
            'actor_email' => $user?->email ?? null,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'details' => $details,
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toIso8601String(),
        ];

        Log::channel('audit')->info(sprintf('Audit: %s on %s#%s by %s', $action, $targetType ?? 'system', $targetId ?? 'N/A', $payload['actor_name']), $payload);
    }
}
