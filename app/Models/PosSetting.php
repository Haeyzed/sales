<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PosSetting Model
 *
 * Represents POS (Point of Sale) settings.
 *
 * @property int $id
 * @property int|null $customerId
 * @property int|null $warehouseId
 * @property int|null $billerId
 * @property int|null $productNumber
 * @property string|null $stripePublicKey
 * @property string|null $stripeSecretKey
 * @property string|null $paypalLiveApiUsername
 * @property string|null $paypalLiveApiPassword
 * @property string|null $paypalLiveApiSecret
 * @property string|null $paymentOptions
 * @property string|null $invoiceOption
 * @property string|null $thermalInvoiceSize
 * @property bool|null $keybordActive
 * @property bool|null $isTable
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 */
class PosSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pos_setting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'warehouse_id',
        'biller_id',
        'product_number',
        'stripe_public_key',
        'stripe_secret_key',
        'paypal_live_api_username',
        'paypal_live_api_password',
        'paypal_live_api_secret',
        'payment_options',
        'invoice_option',
        'thermal_invoice_size',
        'keybord_active',
        'is_table',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'warehouse_id' => 'integer',
            'biller_id' => 'integer',
            'product_number' => 'integer',
            'keybord_active' => 'boolean',
            'is_table' => 'boolean',
        ];
    }
}

