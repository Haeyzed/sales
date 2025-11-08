<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DiscountPlanCustomer Model
 *
 * Represents the pivot table for discount plans and customers.
 *
 * @property int $id
 * @property int $discountPlanId
 * @property int $customerId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read DiscountPlan $discountPlan
 * @property-read Customer $customer
 */
class DiscountPlanCustomer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discount_plan_customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'discount_plan_id',
        'customer_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_plan_id' => 'integer',
            'customer_id' => 'integer',
        ];
    }

    /**
     * Get the discount plan.
     *
     * @return BelongsTo<DiscountPlan, DiscountPlanCustomer>
     */
    public function discountPlan(): BelongsTo
    {
        return $this->belongsTo(DiscountPlan::class);
    }

    /**
     * Get the customer.
     *
     * @return BelongsTo<Customer, DiscountPlanCustomer>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

