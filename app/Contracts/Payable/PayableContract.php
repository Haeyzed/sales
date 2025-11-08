<?php

declare(strict_types=1);

namespace App\Contracts\Payable;

/**
 * PayableContract Interface
 *
 * Contract for payment processing implementations.
 */
interface PayableContract
{
    /**
     * Process a payment.
     *
     * @param mixed $request The payment request data
     * @param mixed $otherRequest Additional request data
     * @return mixed
     */
    public function pay(mixed $request, mixed $otherRequest): mixed;

    /**
     * Cancel a payment.
     *
     * @return mixed
     */
    public function cancel(): mixed;
}

