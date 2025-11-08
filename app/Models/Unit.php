<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Unit Model
 *
 * Represents a measurement unit in the inventory system.
 * Supports unit conversion with base units and operators.
 *
 * @property int $id
 * @property string $unitCode
 * @property string $unitName
 * @property int|null $baseUnit
 * @property string|null $operator
 * @property float|null $operationValue
 * @property bool $isActive
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Unit|null $baseUnitRelation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Unit> $derivedUnits
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 */
class Unit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_code',
        'unit_name',
        'base_unit',
        'operator',
        'operation_value',
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
            'base_unit' => 'integer',
            'operation_value' => 'float',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the base unit for this unit.
     *
     * @return BelongsTo<Unit, Unit>
     */
    public function baseUnitRelation(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit');
    }

    /**
     * Get the units derived from this unit.
     *
     * @return HasMany<Unit>
     */
    public function derivedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, 'base_unit');
    }

    /**
     * Get the products using this unit.
     *
     * @return HasMany<Product>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

