<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Sale Model
 *
 * Represents a sale transaction in the system.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $userId
 * @property int|null $cashRegisterId
 * @property int|null $tableId
 * @property int|null $queue
 * @property int|null $customerId
 * @property int $warehouseId
 * @property int $billerId
 * @property int $item
 * @property float $totalQty
 * @property float $totalDiscount
 * @property float $totalTax
 * @property float $totalPrice
 * @property float|null $orderTaxRate
 * @property float|null $orderTax
 * @property string|null $orderDiscountType
 * @property float|null $orderDiscountValue
 * @property float|null $orderDiscount
 * @property int|null $couponId
 * @property float|null $couponDiscount
 * @property float|null $shippingCost
 * @property float $grandTotal
 * @property int|null $currencyId
 * @property float|null $exchangeRate
 * @property string $saleStatus
 * @property string $paymentStatus
 * @property string|null $billingName
 * @property string|null $billingPhone
 * @property string|null $billingEmail
 * @property string|null $billingAddress
 * @property string|null $billingCity
 * @property string|null $billingState
 * @property string|null $billingCountry
 * @property string|null $billingZip
 * @property string|null $shippingName
 * @property string|null $shippingPhone
 * @property string|null $shippingEmail
 * @property string|null $shippingAddress
 * @property string|null $shippingCity
 * @property string|null $shippingState
 * @property string|null $shippingCountry
 * @property string|null $shippingZip
 * @property string $saleType
 * @property int|null $serviceId
 * @property int|null $waiterId
 * @property float $paidAmount
 * @property string|null $document
 * @property string|null $saleNote
 * @property string|null $staffNote
 * @property int|null $woocommerceOrderId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User $user
 * @property-read CashRegister|null $cashRegister
 * @property-read Table|null $table
 * @property-read Customer|null $customer
 * @property-read Warehouse $warehouse
 * @property-read Biller $biller
 * @property-read Currency|null $currency
 * @property-read Coupon|null $coupon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Payment> $payments
 * @property-read Returns|null $return
 * @property-read Delivery|null $delivery
 */
class Sale extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'user_id',
        'cash_register_id',
        'table_id',
        'queue',
        'customer_id',
        'warehouse_id',
        'biller_id',
        'item',
        'total_qty',
        'total_discount',
        'total_tax',
        'total_price',
        'order_tax_rate',
        'order_tax',
        'order_discount_type',
        'order_discount_value',
        'order_discount',
        'coupon_id',
        'coupon_discount',
        'shipping_cost',
        'grand_total',
        'currency_id',
        'exchange_rate',
        'sale_status',
        'payment_status',
        'billing_name',
        'billing_phone',
        'billing_email',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zip',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zip',
        'sale_type',
        'service_id',
        'waiter_id',
        'paid_amount',
        'document',
        'sale_note',
        'staff_note',
        'woocommerce_order_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'cash_register_id' => 'integer',
            'table_id' => 'integer',
            'queue' => 'integer',
            'customer_id' => 'integer',
            'warehouse_id' => 'integer',
            'biller_id' => 'integer',
            'item' => 'integer',
            'total_qty' => 'float',
            'total_discount' => 'float',
            'total_tax' => 'float',
            'total_price' => 'float',
            'order_tax_rate' => 'float',
            'order_tax' => 'float',
            'order_discount_value' => 'float',
            'order_discount' => 'float',
            'coupon_id' => 'integer',
            'coupon_discount' => 'float',
            'shipping_cost' => 'float',
            'grand_total' => 'float',
            'currency_id' => 'integer',
            'exchange_rate' => 'float',
            'service_id' => 'integer',
            'waiter_id' => 'integer',
            'paid_amount' => 'float',
            'woocommerce_order_id' => 'integer',
        ];
    }

    /**
     * Get the user who created this sale.
     *
     * @return BelongsTo<User, Sale>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cash register.
     *
     * @return BelongsTo<CashRegister, Sale>
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    /**
     * Get the table.
     *
     * @return BelongsTo<Table, Sale>
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the customer.
     *
     * @return BelongsTo<Customer, Sale>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, Sale>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the biller.
     *
     * @return BelongsTo<Biller, Sale>
     */
    public function biller(): BelongsTo
    {
        return $this->belongsTo(Biller::class);
    }

    /**
     * Get the currency.
     *
     * @return BelongsTo<Currency, Sale>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the coupon.
     *
     * @return BelongsTo<Coupon, Sale>
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the products in this sale.
     *
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sales')
            ->withPivot('qty', 'product_batch_id', 'return_qty', 'net_unit_price', 'tax', 'discount', 'tax_rate', 'total', 'is_delivered', 'variant_id', 'imei_number')
            ->withTimestamps();
    }

    /**
     * Get the payments for this sale.
     *
     * @return HasMany<Payment>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the return for this sale.
     *
     * @return HasOne<Returns>
     */
    public function return(): HasOne
    {
        return $this->hasOne(Returns::class, 'sale_id');
    }

    /**
     * Get the delivery for this sale.
     *
     * @return HasOne<Delivery>
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}

