<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PackingSlipProduct Model
 *
 * Represents a product in a packing slip (pivot table).
 *
 * @property int $id
 * @property int $packingSlipId
 * @property int $productId
 * @property int|null $variantId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read PackingSlip $packingSlip
 * @property-read Product $product
 */
class PackingSlipProduct extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'packing_slip_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'packing_slip_id',
        'product_id',
        'variant_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'packing_slip_id' => 'integer',
            'product_id' => 'integer',
            'variant_id' => 'integer',
        ];
    }

    /**
     * Get the packing slip.
     *
     * @return BelongsTo<PackingSlip, PackingSlipProduct>
     */
    public function packingSlip(): BelongsTo
    {
        return $this->belongsTo(PackingSlip::class);
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, PackingSlipProduct>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

