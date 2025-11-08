<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductWarehouse Model
 *
 * Represents the pivot table for products and warehouses.
 *
 * @property int $id
 * @property int $productId
 * @property int|null $productBatchId
 * @property int|null $variantId
 * @property string|null $imeiNumber
 * @property int $warehouseId
 * @property float $qty
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Product $product
 * @property-read Warehouse $warehouse
 * @property-read ProductBatch|null $batch
 */
class ProductWarehouse extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_warehouse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'product_batch_id',
        'variant_id',
        'imei_number',
        'warehouse_id',
        'qty',
        'price',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'product_batch_id' => 'integer',
            'variant_id' => 'integer',
            'warehouse_id' => 'integer',
            'qty' => 'float',
            'price' => 'float',
        ];
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductWarehouse>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, ProductWarehouse>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the batch.
     *
     * @return BelongsTo<ProductBatch, ProductWarehouse>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }

    /**
     * Scope a query to find product with variant in warehouse.
     *
     * @param \Illuminate\Database\Eloquent\Builder<ProductWarehouse> $query
     * @param int $productId
     * @param int $variantId
     * @param int $warehouseId
     * @return \Illuminate\Database\Eloquent\Builder<ProductWarehouse>
     */
    public function scopeFindProductWithVariant($query, int $productId, int $variantId, int $warehouseId)
    {
        return $query->where([
            ['product_id', $productId],
            ['variant_id', $variantId],
            ['warehouse_id', $warehouseId],
        ]);
    }

    /**
     * Scope a query to find product without variant in warehouse.
     *
     * @param \Illuminate\Database\Eloquent\Builder<ProductWarehouse> $query
     * @param int $productId
     * @param int $warehouseId
     * @return \Illuminate\Database\Eloquent\Builder<ProductWarehouse>
     */
    public function scopeFindProductWithoutVariant($query, int $productId, int $warehouseId)
    {
        return $query->where([
            ['product_id', $productId],
            ['warehouse_id', $warehouseId],
        ]);
    }
}

