<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentWithCheque Model
 *
 * Represents a payment made with a cheque.
 *
 * @property int $id
 * @property int $paymentId
 * @property string $chequeNo
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Payment $payment
 */
class PaymentWithCheque extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_with_cheque';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_id',
        'cheque_no',
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
        ];
    }

    /**
     * Get the payment.
     *
     * @return BelongsTo<Payment, PaymentWithCheque>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}

