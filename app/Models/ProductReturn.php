<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductReturn Model
 *
 * Represents a product in a return (pivot table).
 *
 * @property int $id
 * @property int $returnId
 * @property int $productId
 * @property int|null $variantId
 * @property string|null $imeiNumber
 * @property int|null $productBatchId
 * @property float $qty
 * @property int $saleUnitId
 * @property float $netUnitPrice
 * @property float $discount
 * @property float $taxRate
 * @property float $tax
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Returns $return
 * @property-read Product $product
 * @property-read Unit $saleUnit
 * @property-read ProductBatch|null $batch
 */
class ProductReturn extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_returns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'return_id',
        'product_id',
        'variant_id',
        'imei_number',
        'product_batch_id',
        'qty',
        'sale_unit_id',
        'net_unit_price',
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
            'variant_id' => 'integer',
            'product_batch_id' => 'integer',
            'qty' => 'float',
            'sale_unit_id' => 'integer',
            'net_unit_price' => 'float',
            'discount' => 'float',
            'tax_rate' => 'float',
            'tax' => 'float',
            'total' => 'float',
        ];
    }

    /**
     * Get the return.
     *
     * @return BelongsTo<Returns, ProductReturn>
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(Returns::class, 'return_id');
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductReturn>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the sale unit.
     *
     * @return BelongsTo<Unit, ProductReturn>
     */
    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    /**
     * Get the batch.
     *
     * @return BelongsTo<ProductBatch, ProductReturn>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
}

