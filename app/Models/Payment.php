<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Payment Model
 *
 * Represents a payment transaction.
 *
 * @property int $id
 * @property int|null $purchaseId
 * @property int $userId
 * @property int|null $saleId
 * @property int|null $cashRegisterId
 * @property int|null $accountId
 * @property string|null $paymentReceiver
 * @property string|null $paymentReference
 * @property float $amount
 * @property float $usedPoints
 * @property float $change
 * @property string $payingMethod
 * @property string|null $paymentNote
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Purchase|null $purchase
 * @property-read User $user
 * @property-read Sale|null $sale
 * @property-read CashRegister|null $cashRegister
 * @property-read Account|null $account
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_id',
        'user_id',
        'sale_id',
        'cash_register_id',
        'account_id',
        'payment_receiver',
        'payment_reference',
        'amount',
        'used_points',
        'change',
        'paying_method',
        'payment_note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_id' => 'integer',
            'user_id' => 'integer',
            'sale_id' => 'integer',
            'cash_register_id' => 'integer',
            'account_id' => 'integer',
            'amount' => 'float',
            'used_points' => 'float',
            'change' => 'float',
        ];
    }

    /**
     * Get the purchase.
     *
     * @return BelongsTo<Purchase, Payment>
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the user who processed this payment.
     *
     * @return BelongsTo<User, Payment>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sale.
     *
     * @return BelongsTo<Sale, Payment>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the cash register.
     *
     * @return BelongsTo<CashRegister, Payment>
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    /**
     * Get the account.
     *
     * @return BelongsTo<Account, Payment>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}

