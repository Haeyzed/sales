<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Employee Model
 *
 * Represents an employee in the HRM system.
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property int $departmentId
 * @property string|null $email
 * @property string|null $phoneNumber
 * @property int|null $userId
 * @property string|null $staffId
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property bool $isActive
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Department $department
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Payroll> $payrolls
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attendance> $attendances
 */
class Employee extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'department_id',
        'email',
        'phone_number',
        'user_id',
        'staff_id',
        'address',
        'city',
        'country',
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
            'department_id' => 'integer',
            'user_id' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the department.
     *
     * @return BelongsTo<Department, Employee>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user associated with this employee.
     *
     * @return BelongsTo<User, Employee>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payrolls for this employee.
     *
     * @return HasMany<Payroll>
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Get the attendances for this employee.
     *
     * @return HasMany<Attendance>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}

