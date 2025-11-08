<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Account Model
 *
 * Represents a financial account in the accounting system.
 *
 * @property int $id
 * @property string $accountNo
 * @property string $name
 * @property float $initialBalance
 * @property float $totalBalance
 * @property string|null $note
 * @property bool $isDefault
 * @property bool $isActive
 * @property string|null $code
 * @property string|null $type
 * @property int|null $parentAccountId
 * @property bool|null $isPayment
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Account|null $parentAccount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Account> $childAccounts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Payment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Expense> $expenses
 */
class Account extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_no',
        'name',
        'initial_balance',
        'total_balance',
        'note',
        'is_default',
        'is_active',
        'code',
        'type',
        'parent_account_id',
        'is_payment',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'initial_balance' => 'float',
            'total_balance' => 'float',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'parent_account_id' => 'integer',
            'is_payment' => 'boolean',
        ];
    }

    /**
     * Get the parent account.
     *
     * @return BelongsTo<Account, Account>
     */
    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    /**
     * Get the child accounts.
     *
     * @return HasMany<Account>
     */
    public function childAccounts(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_account_id');
    }

    /**
     * Get the payments for this account.
     *
     * @return HasMany<Payment>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the expenses for this account.
     *
     * @return HasMany<Expense>
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}

