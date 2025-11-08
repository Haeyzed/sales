<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductBatch Model
 *
 * Represents a product batch with expiry date tracking.
 *
 * @property int $id
 * @property int $productId
 * @property string $batchNo
 * @property \Illuminate\Support\Carbon|null $expiredDate
 * @property float $qty
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Product $product
 */
class ProductBatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'batch_no',
        'expired_date',
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
            'expired_date' => 'date',
            'qty' => 'float',
        ];
    }

    /**
     * Get the product.
     *
     * @return BelongsTo<Product, ProductBatch>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

