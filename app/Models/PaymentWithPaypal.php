<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentWithPaypal Model
 *
 * Represents a payment made with PayPal.
 *
 * @property int $id
 * @property int $paymentId
 * @property string|null $transactionId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Payment $payment
 */
class PaymentWithPaypal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_with_paypal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_id',
        'transaction_id',
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
     * @return BelongsTo<Payment, PaymentWithPaypal>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}

