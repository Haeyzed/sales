<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductAdjustment Model
 *
 * Represents a product in a stock adjustment.
 *
 * @property int $id
 * @property int $adjustmentId
 * @property int $productId
 * @property int|null $variantId
 * @property float $unitCost
 * @property float $qty
 * @property string $action
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Adjustment $adjustment
 * @property-read Product $product
 */
class ProductAdjustment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_adjustments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'adjustment_id',
        'product_id',
        'variant_id',
        'unit_cost',
        'qty',
        'action',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'adjustment_id' => 'integer',
            'product_id' => 'integer',
            'variant_id' => 'integer',
            'unit_cost' => 'float',
            'qty' => 'float',
        ];
    }

    /**
     * Get the adjustment.
     *
     * @return BelongsTo<Adjustment, ProductAdjustment>
     */
    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(Adjustment::class);
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductAdjustment>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

