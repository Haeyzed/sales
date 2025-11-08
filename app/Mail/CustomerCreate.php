<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * CustomerCreate Mailable
 *
 * Email notification sent when a new customer is created.
 */
class CustomerCreate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The customer data.
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
        $customerData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.customer_create', compact('customerData', 'generalSetting'))
            ->subject('New Customer');
    }
}

