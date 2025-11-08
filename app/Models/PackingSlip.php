<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * PackingSlip Model
 *
 * Represents a packing slip for a sale delivery.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $saleId
 * @property int|null $deliveryId
 * @property float $amount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Sale $sale
 * @property-read Delivery|null $delivery
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 */
class PackingSlip extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'packing_slips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'sale_id',
        'delivery_id',
        'amount',
        'status',
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
            'delivery_id' => 'integer',
            'amount' => 'float',
        ];
    }

    /**
     * Get the sale.
     *
     * @return BelongsTo<Sale, PackingSlip>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the delivery.
     *
     * @return BelongsTo<Delivery, PackingSlip>
     */
    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    /**
     * Get the products in this packing slip.
     *
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'packing_slip_products')
            ->withPivot('variant_id')
            ->withTimestamps();
    }
}

