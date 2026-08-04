<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    public function isEnabled(): bool
    {
        return filled(config('services.recaptcha.secret_key'))
            && filled(config('services.recaptcha.site_key'));
    }

    public function verify(?string $token, ?string $remoteIp = null, string $expectedAction = 'donate'): bool
    {
        if (! $this->isEnabled()) {
            return true;
        }

        if (! filled($token)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $remoteIp,
                ]);

            if (! $response->successful()) {
                Log::warning('reCAPTCHA verification request failed', [
                    'status' => $response->status(),
                ]);

                return false;
            }

            $payload = $response->json();

            if (! ($payload['success'] ?? false)) {
                Log::info('reCAPTCHA verification rejected', [
                    'error_codes' => $payload['error-codes'] ?? [],
                ]);

                return false;
            }

            $minScore = (float) config('services.recaptcha.min_score', 0.5);
            $score = isset($payload['score']) ? (float) $payload['score'] : null;

            // v3 responses include score; v2 checkbox responses do not.
            if ($score !== null && $score < $minScore) {
                Log::info('reCAPTCHA score below threshold', [
                    'score' => $score,
                    'min_score' => $minScore,
                    'action' => $payload['action'] ?? null,
                ]);

                return false;
            }

            $action = $payload['action'] ?? null;
            if ($action !== null && $action !== $expectedAction) {
                Log::info('reCAPTCHA action mismatch', [
                    'expected' => $expectedAction,
                    'actual' => $action,
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA verification error', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
