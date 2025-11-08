<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * ReturnDetails Mailable
 *
 * Email notification sent with return details.
 */
class ReturnDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The return data.
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
        $returnData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.return_details', compact('returnData', 'generalSetting'))
            ->subject('Return Details');
    }
}

