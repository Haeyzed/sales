<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
// Note: Uncomment these when packages are installed:
// use Laravel\Sanctum\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles;

/**
 * User Model
 *
 * Represents a user in the system.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $companyName
 * @property int|null $roleId
 * @property int|null $billerId
 * @property int|null $warehouseId
 * @property int|null $kitchenId
 * @property string|null $serviceStaff
 * @property bool $isActive
 * @property bool $isDeleted
 * @property \Illuminate\Support\Carbon|null $emailVerifiedAt
 * @property string $password
 * @property string|null $twoFactorSecret
 * @property string|null $twoFactorRecoveryCodes
 * @property \Illuminate\Support\Carbon|null $twoFactorConfirmedAt
 * @property string|null $rememberToken
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Biller|null $biller
 * @property-read Warehouse|null $warehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Holiday> $holidays
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Purchase> $purchases
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Customer> $customers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Employee> $employees
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;
    // Note: Add these traits when packages are installed:
    // use HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company_name',
        'role_id',
        'biller_id',
        'warehouse_id',
        'kitchen_id',
        'service_staff',
        'is_active',
        'is_deleted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'role_id' => 'integer',
            'biller_id' => 'integer',
            'warehouse_id' => 'integer',
            'kitchen_id' => 'integer',
            'is_active' => 'boolean',
            'is_deleted' => 'boolean',
        ];
    }

    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the biller.
     *
     * @return BelongsTo<Biller, User>
     */
    public function biller(): BelongsTo
    {
        return $this->belongsTo(Biller::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, User>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the holidays for this user.
     *
     * @return HasMany<Holiday>
     */
    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class);
    }

    /**
     * Get the sales created by this user.
     *
     * @return HasMany<Sale>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the purchases created by this user.
     *
     * @return HasMany<Purchase>
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the customers created by this user.
     *
     * @return HasMany<Customer>
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the employees associated with this user.
     *
     * @return HasMany<Employee>
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
