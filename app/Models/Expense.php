<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Expense Model
 *
 * Represents an expense transaction.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $expenseCategoryId
 * @property int|null $warehouseId
 * @property int|null $accountId
 * @property int $userId
 * @property int|null $cashRegisterId
 * @property float $amount
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read ExpenseCategory $expenseCategory
 * @property-read Warehouse|null $warehouse
 * @property-read Account|null $account
 * @property-read User $user
 * @property-read CashRegister|null $cashRegister
 */
class Expense extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'expenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'expense_category_id',
        'warehouse_id',
        'account_id',
        'user_id',
        'cash_register_id',
        'amount',
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
            'expense_category_id' => 'integer',
            'warehouse_id' => 'integer',
            'account_id' => 'integer',
            'user_id' => 'integer',
            'cash_register_id' => 'integer',
            'amount' => 'float',
        ];
    }

    /**
     * Get the expense category.
     *
     * @return BelongsTo<ExpenseCategory, Expense>
     */
    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, Expense>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the account.
     *
     * @return BelongsTo<Account, Expense>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the user who created this expense.
     *
     * @return BelongsTo<User, Expense>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cash register.
     *
     * @return BelongsTo<CashRegister, Expense>
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }
}

