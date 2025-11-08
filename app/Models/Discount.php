<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Discount Model
 *
 * Represents a discount rule that can be applied to products.
 *
 * @property int $id
 * @property string $name
 * @property string $applicableFor
 * @property string|null $productList
 * @property \Illuminate\Support\Carbon|null $validFrom
 * @property \Illuminate\Support\Carbon|null $validTill
 * @property string $type
 * @property float $value
 * @property int|null $minimumQty
 * @property int|null $maximumQty
 * @property string|null $days
 * @property bool $isActive
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DiscountPlan> $discountPlans
 */
class Discount extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'applicable_for',
        'product_list',
        'valid_from',
        'valid_till',
        'type',
        'value',
        'minimum_qty',
        'maximum_qty',
        'days',
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
            'valid_from' => 'date',
            'valid_till' => 'date',
            'value' => 'float',
            'minimum_qty' => 'integer',
            'maximum_qty' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the discount plans that use this discount.
     *
     * @return BelongsToMany<DiscountPlan>
     */
    public function discountPlans(): BelongsToMany
    {
        return $this->belongsToMany(DiscountPlan::class, 'discount_plan_discounts');
    }
}

