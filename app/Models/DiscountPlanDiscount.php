<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DiscountPlanDiscount Model
 *
 * Represents the pivot table for discount plans and discounts.
 *
 * @property int $id
 * @property int $discountPlanId
 * @property int $discountId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read DiscountPlan $discountPlan
 * @property-read Discount $discount
 */
class DiscountPlanDiscount extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discount_plan_discounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'discount_plan_id',
        'discount_id',
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
            'discount_id' => 'integer',
        ];
    }

    /**
     * Get the discount plan.
     *
     * @return BelongsTo<DiscountPlan, DiscountPlanDiscount>
     */
    public function discountPlan(): BelongsTo
    {
        return $this->belongsTo(DiscountPlan::class);
    }

    /**
     * Get the discount.
     *
     * @return BelongsTo<Discount, DiscountPlanDiscount>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
}

