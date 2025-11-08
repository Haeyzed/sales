<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Deposit Model
 *
 * Represents a customer deposit.
 *
 * @property int $id
 * @property float $amount
 * @property int $customerId
 * @property int $userId
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Customer $customer
 * @property-read User $user
 */
class Deposit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deposits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'customer_id',
        'user_id',
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
            'amount' => 'float',
            'customer_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    /**
     * Get the customer.
     *
     * @return BelongsTo<Customer, Deposit>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who created this deposit.
     *
     * @return BelongsTo<User, Deposit>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

