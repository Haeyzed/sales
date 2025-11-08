<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductVariant Model
 *
 * Represents the pivot table for product variants.
 *
 * @property int $id
 * @property int $productId
 * @property int $variantId
 * @property int|null $position
 * @property string|null $itemCode
 * @property float $additionalCost
 * @property float $additionalPrice
 * @property float $qty
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Product $product
 * @property-read Variant $variant
 */
class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_variants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'variant_id',
        'position',
        'item_code',
        'additional_cost',
        'additional_price',
        'qty',
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
            'variant_id' => 'integer',
            'position' => 'integer',
            'additional_cost' => 'float',
            'additional_price' => 'float',
            'qty' => 'float',
        ];
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductVariant>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant.
     *
     * @return BelongsTo<Variant, ProductVariant>
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Scope a query to find exact product variant.
     *
     * @param \Illuminate\Database\Eloquent\Builder<ProductVariant> $query
     * @param int $productId
     * @param int $variantId
     * @return \Illuminate\Database\Eloquent\Builder<ProductVariant>
     */
    public function scopeFindExactProduct($query, int $productId, int $variantId)
    {
        return $query->where([
            ['product_id', $productId],
            ['variant_id', $variantId],
        ]);
    }

    /**
     * Scope a query to find exact product variant by item code.
     *
     * @param \Illuminate\Database\Eloquent\Builder<ProductVariant> $query
     * @param int $productId
     * @param string $itemCode
     * @return \Illuminate\Database\Eloquent\Builder<ProductVariant>
     */
    public function scopeFindExactProductWithCode($query, int $productId, string $itemCode)
    {
        return $query->where([
            ['product_id', $productId],
            ['item_code', $itemCode],
        ]);
    }
}

