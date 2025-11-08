<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * GiftCard Model
 *
 * Represents a gift card in the system.
 *
 * @property int $id
 * @property string $cardNo
 * @property float $amount
 * @property float $expense
 * @property int|null $customerId
 * @property int|null $userId
 * @property \Illuminate\Support\Carbon|null $expiredDate
 * @property int|null $createdBy
 * @property bool $isActive
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Customer|null $customer
 * @property-read User|null $user
 */
class GiftCard extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gift_cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'card_no',
        'amount',
        'expense',
        'customer_id',
        'user_id',
        'expired_date',
        'created_by',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'expense' => 'float',
            'customer_id' => 'integer',
            'user_id' => 'integer',
            'expired_date' => 'date',
            'created_by' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the customer.
     *
     * @return BelongsTo<Customer, GiftCard>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user.
     *
     * @return BelongsTo<User, GiftCard>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

