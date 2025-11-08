<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * QuotationDetails Mailable
 *
 * Email notification sent with quotation details.
 */
class QuotationDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The quotation data.
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
        $quotationData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.quotation_details', compact('quotationData', 'generalSetting'))
            ->subject('Quotation Details');
    }
}

