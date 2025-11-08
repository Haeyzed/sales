<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * UserDetails Mailable
 *
 * Email notification sent with user details.
 */
class UserDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user data.
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
        $userData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.user_details', compact('userData', 'generalSetting'))
            ->subject('User Details');
    }
}

