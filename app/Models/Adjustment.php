<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Adjustment Model
 *
 * Represents a stock adjustment in a warehouse.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $warehouseId
 * @property string|null $document
 * @property float $totalQty
 * @property int $item
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Warehouse $warehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductAdjustment> $productAdjustments
 */
class Adjustment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adjustments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'warehouse_id',
        'document',
        'total_qty',
        'item',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'warehouse_id' => 'integer',
            'total_qty' => 'float',
            'item' => 'integer',
        ];
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, Adjustment>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product adjustments.
     *
     * @return HasMany<ProductAdjustment>
     */
    public function productAdjustments(): HasMany
    {
        return $this->hasMany(ProductAdjustment::class);
    }
}

