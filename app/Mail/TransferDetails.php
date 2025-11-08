<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * TransferDetails Mailable
 *
 * Email notification sent with transfer details.
 */
class TransferDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The transfer data.
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
        $transferData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.transfer_details', compact('transferData', 'generalSetting'))
            ->subject('Transfer Details');
    }
}

