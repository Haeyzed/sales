<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MoneyTransfer Model
 *
 * Represents a money transfer between accounts.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $fromAccountId
 * @property int $toAccountId
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Account $fromAccount
 * @property-read Account $toAccount
 */
class MoneyTransfer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'money_transfers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'from_account_id',
        'to_account_id',
        'amount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'from_account_id' => 'integer',
            'to_account_id' => 'integer',
            'amount' => 'float',
        ];
    }

    /**
     * Get the source account.
     *
     * @return BelongsTo<Account, MoneyTransfer>
     */
    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    /**
     * Get the destination account.
     *
     * @return BelongsTo<Account, MoneyTransfer>
     */
    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}

