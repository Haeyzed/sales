<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * HolidayApprove Mailable
 *
 * Email notification sent when a holiday/leave request is approved.
 */
class HolidayApprove extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The holiday data.
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
        $holidayData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.holiday_approve', compact('holidayData', 'generalSetting'))
            ->subject('Holiday Approve');
    }
}

