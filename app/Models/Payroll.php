<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Payroll Model
 *
 * Represents a payroll payment to an employee.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $employeeId
 * @property int|null $accountId
 * @property int $userId
 * @property float $amount
 * @property string $payingMethod
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Employee $employee
 * @property-read Account|null $account
 * @property-read User $user
 */
class Payroll extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payrolls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'employee_id',
        'account_id',
        'user_id',
        'amount',
        'paying_method',
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
            'employee_id' => 'integer',
            'account_id' => 'integer',
            'user_id' => 'integer',
            'amount' => 'float',
        ];
    }

    /**
     * Get the employee.
     *
     * @return BelongsTo<Employee, Payroll>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the account.
     *
     * @return BelongsTo<Account, Payroll>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the user who processed this payroll.
     *
     * @return BelongsTo<User, Payroll>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

