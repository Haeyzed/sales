<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PurchaseProductReturn Model
 *
 * Represents a product in a purchase return.
 *
 * @property int $id
 * @property int $returnId
 * @property int $productId
 * @property int|null $productBatchId
 * @property int|null $variantId
 * @property string|null $imeiNumber
 * @property float $qty
 * @property int $purchaseUnitId
 * @property float $netUnitCost
 * @property float $discount
 * @property float $taxRate
 * @property float $tax
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read ReturnPurchase $returnPurchase
 * @property-read Product $product
 * @property-read Unit $purchaseUnit
 * @property-read ProductBatch|null $batch
 */
class PurchaseProductReturn extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_product_return';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'return_id',
        'product_id',
        'product_batch_id',
        'variant_id',
        'imei_number',
        'qty',
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
            'return_id' => 'integer',
            'product_id' => 'integer',
            'product_batch_id' => 'integer',
            'variant_id' => 'integer',
            'qty' => 'float',
            'purchase_unit_id' => 'integer',
            'net_unit_cost' => 'float',
            'discount' => 'float',
            'tax_rate' => 'float',
            'tax' => 'float',
            'total' => 'float',
        ];
    }

    /**
     * Get the return purchase.
     *
     * @return BelongsTo<ReturnPurchase, PurchaseProductReturn>
     */
    public function returnPurchase(): BelongsTo
    {
        return $this->belongsTo(ReturnPurchase::class, 'return_id');
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, PurchaseProductReturn>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the purchase unit.
     *
     * @return BelongsTo<Unit, PurchaseProductReturn>
     */
    public function purchaseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    /**
     * Get the batch.
     *
     * @return BelongsTo<ProductBatch, PurchaseProductReturn>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
}

