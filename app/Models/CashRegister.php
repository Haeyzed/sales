<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * CashRegister Model
 *
 * Represents a cash register for POS transactions.
 *
 * @property int $id
 * @property float $cashInHand
 * @property int $userId
 * @property int $warehouseId
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User $user
 * @property-read Warehouse $warehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Payment> $payments
 */
class CashRegister extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cash_registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cash_in_hand',
        'user_id',
        'warehouse_id',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cash_in_hand' => 'float',
            'user_id' => 'integer',
            'warehouse_id' => 'integer',
        ];
    }

    /**
     * Get the user.
     *
     * @return BelongsTo<User, CashRegister>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, CashRegister>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the sales for this cash register.
     *
     * @return HasMany<Sale>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the payments for this cash register.
     *
     * @return HasMany<Payment>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}

