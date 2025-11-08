<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * TenantCreate Mailable
 *
 * Email notification sent when a new tenant is created (for multi-tenant systems).
 */
class TenantCreate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The tenant data.
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
        $tenantData = $this->data;
        $generalSetting = \App\Models\GeneralSetting::first();
        $companyName = $tenantData['superadmin_company_name'] ?? 'Our Platform';
        return $this->view('emails.tenant_create', compact('tenantData', 'generalSetting'))
            ->subject("Welcome to {$companyName}! Get Started with Your Subscription");
    }
}

