<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * BillerCreate Mailable
 *
 * Email notification sent when a new biller is created.
 */
class BillerCreate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The biller data.
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
        $billerData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.biller_create', compact('billerData', 'generalSetting'))
            ->subject('New Biller');
    }
}

