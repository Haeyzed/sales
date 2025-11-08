<?php

declare(strict_types=1);

namespace App\Contracts\Sms;

/**
 * CheckBalanceInterface
 *
 * Interface for checking SMS service balance.
 */
interface CheckBalanceInterface
{
    /**
     * Get the current balance of the SMS service.
     *
     * @return float|int
     */
    public function balance(): float|int;
}

