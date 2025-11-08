<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Transfer Model
 *
 * Represents a product transfer between warehouses.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $userId
 * @property string $status
 * @property int $fromWarehouseId
 * @property int $toWarehouseId
 * @property int $item
 * @property float $totalQty
 * @property float $totalTax
 * @property float $totalCost
 * @property float|null $shippingCost
 * @property float $grandTotal
 * @property string|null $document
 * @property string|null $note
 * @property bool $isSent
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User $user
 * @property-read Warehouse $fromWarehouse
 * @property-read Warehouse $toWarehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductTransfer> $productTransfers
 */
class Transfer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'user_id',
        'status',
        'from_warehouse_id',
        'to_warehouse_id',
        'item',
        'total_qty',
        'total_tax',
        'total_cost',
        'shipping_cost',
        'grand_total',
        'document',
        'note',
        'is_sent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'from_warehouse_id' => 'integer',
            'to_warehouse_id' => 'integer',
            'item' => 'integer',
            'total_qty' => 'float',
            'total_tax' => 'float',
            'total_cost' => 'float',
            'shipping_cost' => 'float',
            'grand_total' => 'float',
            'is_sent' => 'boolean',
        ];
    }

    /**
     * Get the user who created this transfer.
     *
     * @return BelongsTo<User, Transfer>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the source warehouse.
     *
     * @return BelongsTo<Warehouse, Transfer>
     */
    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    /**
     * Get the destination warehouse.
     *
     * @return BelongsTo<Warehouse, Transfer>
     */
    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    /**
     * Get the product transfers.
     *
     * @return HasMany<ProductTransfer>
     */
    public function productTransfers(): HasMany
    {
        return $this->hasMany(ProductTransfer::class, 'transfer_id');
    }
}

