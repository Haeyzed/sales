<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StockCount Model
 *
 * Represents a stock count/audit in a warehouse.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $warehouseId
 * @property int|null $brandId
 * @property int|null $categoryId
 * @property int $userId
 * @property string $type
 * @property string|null $initialFile
 * @property string|null $finalFile
 * @property string|null $note
 * @property bool $isAdjusted
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Warehouse $warehouse
 * @property-read Brand|null $brand
 * @property-read Category|null $category
 * @property-read User $user
 */
class StockCount extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_counts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'warehouse_id',
        'brand_id',
        'category_id',
        'user_id',
        'type',
        'initial_file',
        'final_file',
        'note',
        'is_adjusted',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'warehouse_id' => 'integer',
            'brand_id' => 'integer',
            'category_id' => 'integer',
            'user_id' => 'integer',
            'is_adjusted' => 'boolean',
        ];
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, StockCount>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the brand.
     *
     * @return BelongsTo<Brand, StockCount>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the category.
     *
     * @return BelongsTo<Category, StockCount>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who created this stock count.
     *
     * @return BelongsTo<User, StockCount>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

