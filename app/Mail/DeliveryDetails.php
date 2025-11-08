<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * DeliveryDetails Mailable
 *
 * Email notification sent with delivery details.
 */
class DeliveryDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The delivery data.
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
        $deliveryData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.delivery_details', compact('deliveryData', 'generalSetting'))
            ->subject('Delivery Details');
    }
}

