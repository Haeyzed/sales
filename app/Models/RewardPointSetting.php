<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * RewardPointSetting Model
 *
 * Represents reward point system settings.
 *
 * @property int $id
 * @property float|null $perPointAmount
 * @property float|null $minimumAmount
 * @property int|null $duration
 * @property string|null $type
 * @property bool|null $isActive
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 */
class RewardPointSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reward_point_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'per_point_amount',
        'minimum_amount',
        'duration',
        'type',
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
            'per_point_amount' => 'float',
            'minimum_amount' => 'float',
            'duration' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}

