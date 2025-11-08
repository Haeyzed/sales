<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * PaymentDetails Mailable
 *
 * Email notification sent with payment details.
 */
class PaymentDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The payment data.
     *
     * @var array<string, mixed>
     */
    public array $data;

    /**
     * Create a new message instance.
     *
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        $paymentData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.payment_details', compact('paymentData', 'generalSetting'))
            ->subject('Payment Details');
    }
}

