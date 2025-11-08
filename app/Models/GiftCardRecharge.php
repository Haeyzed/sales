<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * GiftCardRecharge Model
 *
 * Represents a gift card recharge transaction.
 *
 * @property int $id
 * @property int $giftCardId
 * @property float $amount
 * @property int $userId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read GiftCard $giftCard
 * @property-read User $user
 */
class GiftCardRecharge extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gift_card_recharges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gift_card_id',
        'amount',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gift_card_id' => 'integer',
            'amount' => 'float',
            'user_id' => 'integer',
        ];
    }

    /**
     * Get the gift card.
     *
     * @return BelongsTo<GiftCard, GiftCardRecharge>
     */
    public function giftCard(): BelongsTo
    {
        return $this->belongsTo(GiftCard::class);
    }

    /**
     * Get the user who created this recharge.
     *
     * @return BelongsTo<User, GiftCardRecharge>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

