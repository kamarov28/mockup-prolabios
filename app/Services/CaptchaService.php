<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CaptchaService
{
    /** Minimum reCAPTCHA v3 score to be considered human (0.0–1.0; higher = more confident) */
    private const MIN_HUMAN_SCORE = 0.5;

    /**
     * Verify CAPTCHA token (reCAPTCHA v3 or Cloudflare Turnstile) if enabled in config.
     * Gracefully passes in local/testing when no secret keys are configured.
     */
    public static function verify(Request $request): bool
    {
        $recaptchaSecret = config('services.recaptcha.secret');
        $turnstileSecret = config('services.turnstile.secret');

        // If no CAPTCHA service configured in .env, rely on Honeypot + Rate Limiter
        if (empty($recaptchaSecret) && empty($turnstileSecret)) {
            return true;
        }

        // 1. Google reCAPTCHA v3 Verification
        if (! empty($recaptchaSecret)) {
            $token = $request->input('g-recaptcha-response');
            if (empty($token)) {
                Log::warning('Captcha failed: Missing reCAPTCHA token.', ['ip' => $request->ip()]);

                return false;
            }

            try {
                $response = Http::asForm()
                    ->timeout(5)
                    ->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret'   => $recaptchaSecret,
                        'response' => $token,
                        'remoteip' => $request->ip(),
                    ]);

                $data    = $response->json();
                $success = (bool) ($data['success'] ?? false);
                $score   = (float) ($data['score'] ?? 0.0);

                if (! $success || $score < self::MIN_HUMAN_SCORE) {
                    Log::warning('Captcha verification failed or low score.', [
                        'ip'     => $request->ip(),
                        'score'  => $score,
                        'errors' => $data['error-codes'] ?? [],
                    ]);

                    return false;
                }

                return true;
            } catch (\Throwable $e) {
                Log::error('Error contacting reCAPTCHA API: '.$e->getMessage());

                // Fail open on network timeouts so legitimate users are not blocked
                return true;
            }
        }

        // 2. Cloudflare Turnstile Verification
        if (! empty($turnstileSecret)) {
            $token = $request->input('cf-turnstile-response');
            if (empty($token)) {
                Log::warning('Captcha failed: Missing Turnstile token.', ['ip' => $request->ip()]);

                return false;
            }

            try {
                $response = Http::asForm()
                    ->timeout(5)
                    ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                        'secret'   => $turnstileSecret,
                        'response' => $token,
                        'remoteip' => $request->ip(),
                    ]);

                $data = $response->json();

                return (bool) ($data['success'] ?? false);
            } catch (\Throwable $e) {
                Log::error('Error contacting Turnstile API: '.$e->getMessage());

                return true;
            }
        }

        return true;
    }
}
