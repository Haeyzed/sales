<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Challan Model
 *
 * Represents a challan (delivery document) for courier services.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $courierId
 * @property string $status
 * @property string|null $packingSlipList
 * @property string|null $amountList
 * @property string|null $cashList
 * @property string|null $chequeList
 * @property string|null $onlinePaymentList
 * @property string|null $deliveryChargeList
 * @property string|null $statusList
 * @property \Illuminate\Support\Carbon|null $closingDate
 * @property int|null $createdById
 * @property int|null $closedById
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Courier $courier
 * @property-read User $createdBy
 * @property-read User|null $closedBy
 */
class Challan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'challans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'courier_id',
        'status',
        'packing_slip_list',
        'amount_list',
        'cash_list',
        'cheque_list',
        'online_payment_list',
        'delivery_charge_list',
        'status_list',
        'closing_date',
        'created_by_id',
        'closed_by_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'courier_id' => 'integer',
            'closing_date' => 'date',
            'created_by_id' => 'integer',
            'closed_by_id' => 'integer',
        ];
    }

    /**
     * Get the courier.
     *
     * @return BelongsTo<Courier, Challan>
     */
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * Get the user who created this challan.
     *
     * @return BelongsTo<User, Challan>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the user who closed this challan.
     *
     * @return BelongsTo<User, Challan>
     */
    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_id');
    }
}

