<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Quotation Model
 *
 * Represents a quotation for products.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $userId
 * @property int|null $billerId
 * @property int|null $supplierId
 * @property int|null $customerId
 * @property int $warehouseId
 * @property int $item
 * @property float $totalQty
 * @property float $totalDiscount
 * @property float $totalTax
 * @property float $totalPrice
 * @property float|null $orderTaxRate
 * @property float|null $orderTax
 * @property float|null $orderDiscount
 * @property float|null $shippingCost
 * @property float $grandTotal
 * @property string $quotationStatus
 * @property string|null $document
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User $user
 * @property-read Biller|null $biller
 * @property-read Supplier|null $supplier
 * @property-read Customer|null $customer
 * @property-read Warehouse $warehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductQuotation> $productQuotations
 */
class Quotation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quotations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'user_id',
        'biller_id',
        'supplier_id',
        'customer_id',
        'warehouse_id',
        'item',
        'total_qty',
        'total_discount',
        'total_tax',
        'total_price',
        'order_tax_rate',
        'order_tax',
        'order_discount',
        'shipping_cost',
        'grand_total',
        'quotation_status',
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
            'biller_id' => 'integer',
            'supplier_id' => 'integer',
            'customer_id' => 'integer',
            'warehouse_id' => 'integer',
            'item' => 'integer',
            'total_qty' => 'float',
            'total_discount' => 'float',
            'total_tax' => 'float',
            'total_price' => 'float',
            'order_tax_rate' => 'float',
            'order_tax' => 'float',
            'order_discount' => 'float',
            'shipping_cost' => 'float',
            'grand_total' => 'float',
        ];
    }

    /**
     * Get the user who created this quotation.
     *
     * @return BelongsTo<User, Quotation>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the biller.
     *
     * @return BelongsTo<Biller, Quotation>
     */
    public function biller(): BelongsTo
    {
        return $this->belongsTo(Biller::class);
    }

    /**
     * Get the supplier.
     *
     * @return BelongsTo<Supplier, Quotation>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the customer.
     *
     * @return BelongsTo<Customer, Quotation>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, Quotation>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product quotations.
     *
     * @return HasMany<ProductQuotation>
     */
    public function productQuotations(): HasMany
    {
        return $this->hasMany(ProductQuotation::class, 'quotation_id');
    }
}

