<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * LogMessage Mailable
 *
 * Email notification sent for log messages.
 */
class LogMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The log data.
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
        $logData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.log_message', compact('logData', 'generalSetting'))
            ->subject('New Log Message');
    }
}

