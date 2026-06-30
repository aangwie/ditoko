<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Get the Midtrans client key for frontend.
     */
    public function getClientKey(): string
    {
        return Config::$clientKey;
    }

    /**
     * Generate Snap token for a transaction.
     */
    public function createSnapToken(array $orderData): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderData['order_number'],
                'gross_amount' => $orderData['total_price'],
            ],
            'customer_details' => [
                'first_name' => $orderData['customer_name'],
                'email' => $orderData['customer_email'],
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
