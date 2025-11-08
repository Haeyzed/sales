<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductSale Model
 *
 * Represents the pivot table for products and sales.
 *
 * @property int $id
 * @property int $saleId
 * @property int $productId
 * @property int|null $productBatchId
 * @property int|null $variantId
 * @property string|null $imeiNumber
 * @property float $qty
 * @property float $returnQty
 * @property int $saleUnitId
 * @property float $netUnitPrice
 * @property float $discount
 * @property float $taxRate
 * @property float $tax
 * @property float $total
 * @property bool|null $isPacking
 * @property bool $isDelivered
 * @property int|null $toppingId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Sale $sale
 * @property-read Product $product
 * @property-read Unit $saleUnit
 * @property-read ProductBatch|null $batch
 */
class ProductSale extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_batch_id',
        'variant_id',
        'imei_number',
        'qty',
        'return_qty',
        'sale_unit_id',
        'net_unit_price',
        'discount',
        'tax_rate',
        'tax',
        'total',
        'is_packing',
        'is_delivered',
        'topping_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sale_id' => 'integer',
            'product_id' => 'integer',
            'product_batch_id' => 'integer',
            'variant_id' => 'integer',
            'qty' => 'float',
            'return_qty' => 'float',
            'sale_unit_id' => 'integer',
            'net_unit_price' => 'float',
            'discount' => 'float',
            'tax_rate' => 'float',
            'tax' => 'float',
            'total' => 'float',
            'is_packing' => 'boolean',
            'is_delivered' => 'boolean',
            'topping_id' => 'integer',
        ];
    }

    /**
     * Get the sale.
     *
     * @return BelongsTo<Sale, ProductSale>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductSale>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the sale unit.
     *
     * @return BelongsTo<Unit, ProductSale>
     */
    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    /**
     * Get the batch.
     *
     * @return BelongsTo<ProductBatch, ProductSale>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
}

