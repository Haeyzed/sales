<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductQuotation Model
 *
 * Represents a product in a quotation (pivot table).
 *
 * @property int $id
 * @property int $quotationId
 * @property int $productId
 * @property int|null $productBatchId
 * @property int|null $variantId
 * @property float $qty
 * @property int $saleUnitId
 * @property float $netUnitPrice
 * @property float $discount
 * @property float $taxRate
 * @property float $tax
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Quotation $quotation
 * @property-read Product $product
 * @property-read Unit $saleUnit
 * @property-read ProductBatch|null $batch
 */
class ProductQuotation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_quotation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quotation_id',
        'product_id',
        'product_batch_id',
        'variant_id',
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
            'quotation_id' => 'integer',
            'product_id' => 'integer',
            'product_batch_id' => 'integer',
            'variant_id' => 'integer',
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
     * Get the quotation.
     *
     * @return BelongsTo<Quotation, ProductQuotation>
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductQuotation>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the sale unit.
     *
     * @return BelongsTo<Unit, ProductQuotation>
     */
    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    /**
     * Get the batch.
     *
     * @return BelongsTo<ProductBatch, ProductQuotation>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
}

