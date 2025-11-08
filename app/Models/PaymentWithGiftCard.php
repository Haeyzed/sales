<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentWithGiftCard Model
 *
 * Represents a payment made with a gift card.
 *
 * @property int $id
 * @property int $paymentId
 * @property int $giftCardId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Payment $payment
 * @property-read GiftCard $giftCard
 */
class PaymentWithGiftCard extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_with_gift_card';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_id',
        'gift_card_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payment_id' => 'integer',
            'gift_card_id' => 'integer',
        ];
    }

    /**
     * Get the payment.
     *
     * @return BelongsTo<Payment, PaymentWithGiftCard>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the gift card.
     *
     * @return BelongsTo<GiftCard, PaymentWithGiftCard>
     */
    public function giftCard(): BelongsTo
    {
        return $this->belongsTo(GiftCard::class);
    }
}

