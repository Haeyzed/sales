<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * GeneralSetting Model
 *
 * Represents general application settings.
 *
 * @property int $id
 * @property string|null $siteTitle
 * @property string|null $siteLogo
 * @property bool|null $isRtl
 * @property string|null $currency
 * @property string|null $currencyPosition
 * @property string|null $staffAccess
 * @property bool|null $withoutStock
 * @property bool|null $isPackingSlip
 * @property string|null $dateFormat
 * @property string|null $theme
 * @property string|null $modules
 * @property string|null $developedBy
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $freeTrialLimit
 * @property int|null $packageId
 * @property string|null $invoiceFormat
 * @property int|null $decimal
 * @property string|null $state
 * @property \Illuminate\Support\Carbon|null $expiryDate
 * @property string|null $expiryType
 * @property int|null $expiryValue
 * @property string|null $subscriptionType
 * @property string|null $metaTitle
 * @property string|null $metaDescription
 * @property string|null $activePaymentGateway
 * @property string|null $stripePublicKey
 * @property string|null $stripeSecretKey
 * @property string|null $paypalClientId
 * @property string|null $paypalClientSecret
 * @property string|null $razorpayNumber
 * @property string|null $razorpayKey
 * @property string|null $razorpaySecret
 * @property bool|null $isZatca
 * @property string|null $companyName
 * @property string|null $vatRegistrationNumber
 * @property string|null $dedicatedIp
 * @property string|null $paystackPublicKey
 * @property string|null $paystackSecretKey
 * @property string|null $paydunyaMasterKey
 * @property string|null $paydunyaPublicKey
 * @property string|null $paydunyaSecretKey
 * @property string|null $paydunyaToken
 * @property string|null $sslStoreId
 * @property string|null $sslStorePassword
 * @property string|null $appKey
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 */
class GeneralSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'general_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_title',
        'site_logo',
        'is_rtl',
        'currency',
        'currency_position',
        'staff_access',
        'without_stock',
        'is_packing_slip',
        'date_format',
        'theme',
        'modules',
        'developed_by',
        'phone',
        'email',
        'free_trial_limit',
        'package_id',
        'invoice_format',
        'decimal',
        'state',
        'expiry_date',
        'expiry_type',
        'expiry_value',
        'subscription_type',
        'meta_title',
        'meta_description',
        'active_payment_gateway',
        'stripe_public_key',
        'stripe_secret_key',
        'paypal_client_id',
        'paypal_client_secret',
        'razorpay_number',
        'razorpay_key',
        'razorpay_secret',
        'is_zatca',
        'company_name',
        'vat_registration_number',
        'dedicated_ip',
        'paystack_public_key',
        'paystack_secret_key',
        'paydunya_master_key',
        'paydunya_public_key',
        'paydunya_secret_key',
        'paydunya_token',
        'ssl_store_id',
        'ssl_store_password',
        'app_key',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_rtl' => 'boolean',
            'without_stock' => 'boolean',
            'is_packing_slip' => 'boolean',
            'free_trial_limit' => 'integer',
            'package_id' => 'integer',
            'decimal' => 'integer',
            'expiry_date' => 'date',
            'expiry_value' => 'integer',
            'is_zatca' => 'boolean',
        ];
    }
}

