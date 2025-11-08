<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Attendance Model
 *
 * Represents an employee attendance record.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property int $employeeId
 * @property int $userId
 * @property \Illuminate\Support\Carbon|null $checkin
 * @property \Illuminate\Support\Carbon|null $checkout
 * @property string $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Employee $employee
 * @property-read User $user
 */
class Attendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'employee_id',
        'user_id',
        'checkin',
        'checkout',
        'status',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'employee_id' => 'integer',
            'user_id' => 'integer',
            'checkin' => 'datetime',
            'checkout' => 'datetime',
        ];
    }

    /**
     * Get the employee.
     *
     * @return BelongsTo<Employee, Attendance>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who recorded this attendance.
     *
     * @return BelongsTo<User, Attendance>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

