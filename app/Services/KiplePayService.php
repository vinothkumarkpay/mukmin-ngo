<?php

namespace App\Services;

class KiplePayService
{
    protected $merchantId;
    protected $secretKey;
    protected $baseUrl;

    public function __construct($merchantId = null, $secretKey = null)
    {
        $this->merchantId = $merchantId ?: config('services.kiplepay.merchant_id');
        $this->secretKey = $secretKey ?: config('services.kiplepay.secret_key');
        $this->baseUrl = config('services.kiplepay.url');
    }

    public static function make($type = 'default')
    {
        $merchantId = config("services.kiplepay.merchant_id_{$type}", config('services.kiplepay.merchant_id'));
        $secretKey = config("services.kiplepay.secret_key_{$type}", config('services.kiplepay.secret_key'));

        return new self($merchantId, $secretKey);
    }

    public function preparePayment($orderNo, $amount, $description, $customerName, $customerEmail = null, $returnUrl = null, $callbackUrl = null)
    {
        $hashValue = $this->generateHash($orderNo, $amount);

        return [
            'url' => $this->baseUrl . '/wcgatewayinit.php',
            'params' => [
                'ord_mercID' => $this->merchantId,
                'ord_mercref' => $orderNo,
                'ord_totalamt' => number_format($amount, 2, '.', ''),
                'ord_gstamt' => '0.00',
                'ord_date' => date('Y-m-d'),
                'ord_shipname' => $customerName,
                'ord_returnURL' => $returnUrl,
                'merchant_hashvalue' => $hashValue,
                'ord_email' => $customerEmail,
                'dynamic_callback_url' => $callbackUrl,
            ],
        ];
    }

    public function generateHash($orderNo, $amount)
    {
        $amountWithoutDecimal = number_format($amount, 2, '', '');

        return sha1($this->secretKey . $this->merchantId . $orderNo . $amountWithoutDecimal);
    }

    public function validateCallback($data)
    {
        $orderNo = $data['ord_mercref'] ?? '';
        $amount = $data['ord_totalamt'] ?? '';
        $receivedHash = $data['ord_key'] ?? '';

        $amountWithoutDecimal = number_format((float) $amount, 2, '', '');
        $calculatedHash = sha1($this->secretKey . $this->merchantId . $orderNo . $amountWithoutDecimal);

        return $receivedHash === $calculatedHash;
    }
}
