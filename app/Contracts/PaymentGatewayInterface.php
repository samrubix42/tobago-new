<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Start payment for an order and return gateway redirect details.
     *
     * @return array{success: bool, redirect_url?: string, transaction_id?: string, message?: string}
     */
    public function initiatePayment(Order $order): array;

    /**
     * Verify transaction status at payment gateway.
     *
     * @return array{success: bool, status: string, payload?: array, message?: string}
     */
    public function verifyPayment(string $merchantTransactionId): array;
}
