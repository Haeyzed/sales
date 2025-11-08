<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Holiday Model
 *
 * Represents a holiday/leave request for an employee.
 *
 * @property int $id
 * @property int $userId
 * @property \Illuminate\Support\Carbon $fromDate
 * @property \Illuminate\Support\Carbon $toDate
 * @property string|null $note
 * @property bool $isApproved
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User $user
 */
class Holiday extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'holidays';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'note',
        'is_approved',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'from_date' => 'date',
            'to_date' => 'date',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Get the user.
     *
     * @return BelongsTo<User, Holiday>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

