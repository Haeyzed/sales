<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * CustomerDeposit Mailable
 *
 * Email notification sent when a customer deposit is made.
 */
class CustomerDeposit extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The deposit data.
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
        $depositData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.customer_deposit', compact('depositData', 'generalSetting'))
            ->subject('Customer Deposit');
    }
}

