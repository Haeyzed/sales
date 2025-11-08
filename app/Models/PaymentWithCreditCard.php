<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentWithCreditCard Model
 *
 * Represents a payment made with a credit card (Stripe).
 *
 * @property int $id
 * @property int $paymentId
 * @property int|null $customerId
 * @property string|null $customerStripeId
 * @property string|null $chargeId
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Payment $payment
 * @property-read Customer|null $customer
 */
class PaymentWithCreditCard extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_with_credit_card';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_id',
        'customer_id',
        'customer_stripe_id',
        'charge_id',
        'data',
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
            'customer_id' => 'integer',
        ];
    }

    /**
     * Get the payment.
     *
     * @return BelongsTo<Payment, PaymentWithCreditCard>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the customer.
     *
     * @return BelongsTo<Customer, PaymentWithCreditCard>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

