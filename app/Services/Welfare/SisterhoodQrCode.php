<?php

namespace App\Services\Welfare;

use App\Models\SisterhoodRegistration;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class SisterhoodQrCode
{
    public function payload(SisterhoodRegistration $registration): string
    {
        return implode("\n", [
            'The Sisterhood Collective 2026',
            'Name: ' . $registration->full_name,
            'Preferred name: ' . $registration->preferred_name,
            'Order: ' . $registration->order_id,
            'Email: ' . $registration->email,
            'Check-in: ' . $registration->check_in_code,
        ]);
    }

    public function png(SisterhoodRegistration $registration): string
    {
        $options = new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'outputBase64' => false,
            'scale' => 8,
        ]);

        return (new QRCode($options))->render($this->payload($registration));
    }

    public function codeFromScan(string $raw): ?string
    {
        $raw = trim($raw);

        if (preg_match('/Check-in:\s*([A-F0-9]{12})/i', $raw, $matches)) {
            return strtoupper($matches[1]);
        }

        if (preg_match('/^[A-F0-9]{12}$/i', $raw)) {
            return strtoupper($raw);
        }

        return null;
    }
}
