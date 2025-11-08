<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * PayrollDetails Mailable
 *
 * Email notification sent with payroll details.
 */
class PayrollDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The payroll data.
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
        $payrollData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.payroll_details', compact('payrollData', 'generalSetting'))
            ->subject('Payroll Details');
    }
}

