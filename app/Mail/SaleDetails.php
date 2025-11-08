<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * SaleDetails Mailable
 *
 * Email notification sent with sale details.
 */
class SaleDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The sale data.
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
        $saleData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        return $this->view('emails.sale_details', compact('saleData', 'generalSetting'))
            ->subject('Sale Details');
    }
}

