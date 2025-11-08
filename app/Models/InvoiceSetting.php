<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * InvoiceSetting Model
 *
 * Represents invoice template and formatting settings.
 *
 * @property int $id
 * @property string $templateName
 * @property string $invoiceName
 * @property string|null $invoiceLogo
 * @property string|null $fileType
 * @property string|null $prefix
 * @property int|null $numberOfDigit
 * @property string|null $numberingType
 * @property int|null $startNumber
 * @property int $status
 * @property string|null $headerText
 * @property string|null $headerTitle
 * @property string|null $footerText
 * @property string|null $footerTitle
 * @property bool|null $showBarcode
 * @property bool|null $showQrCode
 * @property bool|null $isDefault
 * @property bool|null $showCustomerDetails
 * @property bool|null $showShippingDetails
 * @property bool|null $showPaymentInfo
 * @property bool|null $showDiscount
 * @property bool|null $showTaxInfo
 * @property bool|null $showDescription
 * @property bool|null $showBillingInfo
 * @property bool|null $showColumn
 * @property string|null $previewInvoice
 * @property bool|null $showInWords
 * @property string|null $companyLogo
 * @property int|null $logoHeight
 * @property int|null $logoWidth
 * @property string|null $primaryColor
 * @property string|null $textColor
 * @property string|null $secondaryColor
 * @property string|null $size
 * @property string|null $invoiceDateFormat
 * @property int|null $createdBy
 * @property int|null $updatedBy
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User|null $creator
 * @property-read User|null $updater
 */
class InvoiceSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoice_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'template_name',
        'invoice_name',
        'invoice_logo',
        'file_type',
        'prefix',
        'number_of_digit',
        'numbering_type',
        'start_number',
        'status',
        'header_text',
        'header_title',
        'footer_text',
        'footer_title',
        'show_barcode',
        'show_qr_code',
        'is_default',
        'show_customer_details',
        'show_shipping_details',
        'show_payment_info',
        'show_discount',
        'show_tax_info',
        'show_description',
        'show_billing_info',
        'show_column',
        'preview_invoice',
        'show_in_words',
        'company_logo',
        'logo_height',
        'logo_width',
        'primary_color',
        'text_color',
        'secondary_color',
        'size',
        'invoice_date_format',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number_of_digit' => 'integer',
            'start_number' => 'integer',
            'status' => 'integer',
            'show_barcode' => 'boolean',
            'show_qr_code' => 'boolean',
            'is_default' => 'boolean',
            'show_customer_details' => 'boolean',
            'show_shipping_details' => 'boolean',
            'show_payment_info' => 'boolean',
            'show_discount' => 'boolean',
            'show_tax_info' => 'boolean',
            'show_description' => 'boolean',
            'show_billing_info' => 'boolean',
            'show_column' => 'boolean',
            'show_in_words' => 'boolean',
            'logo_height' => 'integer',
            'logo_width' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }

    /**
     * Get the user who created this setting.
     *
     * @return BelongsTo<User, InvoiceSetting>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated this setting.
     *
     * @return BelongsTo<User, InvoiceSetting>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the active invoice setting.
     *
     * @return InvoiceSetting|null
     */
    public static function activeSetting(): ?self
    {
        $settings = static::where('status', 1)->first();
        if ($settings === null) {
            $settings = static::where('is_default', true)->first();
        }

        return $settings;
    }
}

