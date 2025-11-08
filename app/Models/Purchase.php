<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Purchase Model
 *
 * Represents a purchase order from a supplier.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $userId
 * @property int $warehouseId
 * @property int $supplierId
 * @property int|null $currencyId
 * @property float|null $exchangeRate
 * @property int $item
 * @property float $totalQty
 * @property float $totalDiscount
 * @property float $totalTax
 * @property float $totalCost
 * @property float|null $orderTaxRate
 * @property float|null $orderTax
 * @property float|null $orderDiscount
 * @property float|null $shippingCost
 * @property float $grandTotal
 * @property float $paidAmount
 * @property string $status
 * @property string $paymentStatus
 * @property string|null $document
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User $user
 * @property-read Warehouse $warehouse
 * @property-read Supplier $supplier
 * @property-read Currency|null $currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ReturnPurchase> $returns
 */
class Purchase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'user_id',
        'warehouse_id',
        'supplier_id',
        'currency_id',
        'exchange_rate',
        'item',
        'total_qty',
        'total_discount',
        'total_tax',
        'total_cost',
        'order_tax_rate',
        'order_tax',
        'order_discount',
        'shipping_cost',
        'grand_total',
        'paid_amount',
        'status',
        'payment_status',
        'document',
        'note',
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
            'warehouse_id' => 'integer',
            'supplier_id' => 'integer',
            'currency_id' => 'integer',
            'exchange_rate' => 'float',
            'item' => 'integer',
            'total_qty' => 'float',
            'total_discount' => 'float',
            'total_tax' => 'float',
            'total_cost' => 'float',
            'order_tax_rate' => 'float',
            'order_tax' => 'float',
            'order_discount' => 'float',
            'shipping_cost' => 'float',
            'grand_total' => 'float',
            'paid_amount' => 'float',
        ];
    }

    /**
     * Get the user who created this purchase.
     *
     * @return BelongsTo<User, Purchase>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, Purchase>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the supplier.
     *
     * @return BelongsTo<Supplier, Purchase>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the currency.
     *
     * @return BelongsTo<Currency, Purchase>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the products in this purchase.
     *
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_purchases')
            ->withPivot('qty', 'tax', 'tax_rate', 'discount', 'total', 'variant_id', 'product_batch_id', 'imei_number')
            ->withTimestamps();
    }

    /**
     * Get the return purchases for this purchase.
     *
     * @return HasMany<ReturnPurchase>
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ReturnPurchase::class, 'purchase_id');
    }
}

