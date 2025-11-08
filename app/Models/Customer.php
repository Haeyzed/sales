<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Customer Model
 *
 * Represents a customer in the system.
 *
 * @property int $id
 * @property int $customerGroupId
 * @property int|null $userId
 * @property string $name
 * @property string|null $companyName
 * @property string|null $email
 * @property string $type
 * @property string $phoneNumber
 * @property string|null $taxNo
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postalCode
 * @property string|null $country
 * @property float $points
 * @property float $deposit
 * @property float $expense
 * @property string|null $wishlist
 * @property bool $isActive
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read CustomerGroup $customerGroup
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DiscountPlan> $discountPlans
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_group_id',
        'user_id',
        'name',
        'company_name',
        'email',
        'type',
        'phone_number',
        'tax_no',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'points',
        'deposit',
        'expense',
        'wishlist',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'customer_group_id' => 'integer',
            'user_id' => 'integer',
            'points' => 'float',
            'deposit' => 'float',
            'expense' => 'float',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the customer group.
     *
     * @return BelongsTo<CustomerGroup, Customer>
     */
    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    /**
     * Get the user associated with this customer.
     *
     * @return BelongsTo<User, Customer>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sales for this customer.
     *
     * @return HasMany<Sale>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the discount plans for this customer.
     *
     * @return BelongsToMany<DiscountPlan>
     */
    public function discountPlans(): BelongsToMany
    {
        return $this->belongsToMany(DiscountPlan::class, 'discount_plan_customers');
    }
}

