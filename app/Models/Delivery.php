<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Delivery Model
 *
 * Represents a delivery for a sale.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $saleId
 * @property string|null $packingSlipIds
 * @property int $userId
 * @property string $address
 * @property int|null $courierId
 * @property string|null $deliveredBy
 * @property string|null $recievedBy
 * @property string|null $file
 * @property string $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Sale $sale
 * @property-read User $user
 * @property-read Courier|null $courier
 */
class Delivery extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deliveries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'sale_id',
        'packing_slip_ids',
        'user_id',
        'address',
        'courier_id',
        'delivered_by',
        'recieved_by',
        'file',
        'status',
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
            'sale_id' => 'integer',
            'user_id' => 'integer',
            'courier_id' => 'integer',
        ];
    }

    /**
     * Get the sale.
     *
     * @return BelongsTo<Sale, Delivery>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the user.
     *
     * @return BelongsTo<User, Delivery>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courier.
     *
     * @return BelongsTo<Courier, Delivery>
     */
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }
}

