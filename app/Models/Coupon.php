<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Coupon Model
 *
 * Represents a discount coupon.
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property float $amount
 * @property float|null $minimumAmount
 * @property int|null $userId
 * @property int $quantity
 * @property int $used
 * @property \Illuminate\Support\Carbon|null $expiredDate
 * @property bool $isActive
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 */
class Coupon extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'amount',
        'minimum_amount',
        'user_id',
        'quantity',
        'used',
        'expired_date',
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
            'minimum_amount' => 'float',
            'user_id' => 'integer',
            'quantity' => 'integer',
            'used' => 'integer',
            'expired_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user.
     *
     * @return BelongsTo<User, Coupon>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sales using this coupon.
     *
     * @return HasMany<Sale>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}

