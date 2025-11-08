<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductPurchase Model
 *
 * Represents a product in a purchase (pivot table).
 *
 * @property int $id
 * @property int $purchaseId
 * @property int $productId
 * @property int|null $productBatchId
 * @property int|null $variantId
 * @property string|null $imeiNumber
 * @property float $qty
 * @property float $recieved
 * @property float $returnQty
 * @property int $purchaseUnitId
 * @property float $netUnitCost
 * @property float $discount
 * @property float $taxRate
 * @property float $tax
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Purchase $purchase
 * @property-read Product $product
 * @property-read Unit $purchaseUnit
 * @property-read ProductBatch|null $batch
 */
class ProductPurchase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_id',
        'product_id',
        'product_batch_id',
        'variant_id',
        'imei_number',
        'qty',
        'recieved',
        'return_qty',
        'purchase_unit_id',
        'net_unit_cost',
        'discount',
        'tax_rate',
        'tax',
        'total',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_id' => 'integer',
            'product_id' => 'integer',
            'product_batch_id' => 'integer',
            'variant_id' => 'integer',
            'qty' => 'float',
            'recieved' => 'float',
            'return_qty' => 'float',
            'purchase_unit_id' => 'integer',
            'net_unit_cost' => 'float',
            'discount' => 'float',
            'tax_rate' => 'float',
            'tax' => 'float',
            'total' => 'float',
        ];
    }

    /**
     * Get the purchase.
     *
     * @return BelongsTo<Purchase, ProductPurchase>
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductPurchase>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the purchase unit.
     *
     * @return BelongsTo<Unit, ProductPurchase>
     */
    public function purchaseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    /**
     * Get the batch.
     *
     * @return BelongsTo<ProductBatch, ProductPurchase>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
}

