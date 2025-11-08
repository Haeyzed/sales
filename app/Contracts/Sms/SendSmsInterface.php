<?php

declare(strict_types=1);

namespace App\Contracts\Sms;

/**
 * SendSmsInterface
 *
 * Interface for sending SMS messages.
 */
interface SendSmsInterface
{
    /**
     * Send an SMS message.
     *
     * @param array<string, mixed> $data The SMS data (phone, message, etc.)
     * @return mixed
     */
    public function send(array $data): mixed;
}

