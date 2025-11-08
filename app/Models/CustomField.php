<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CustomField Model
 *
 * Represents a custom field definition.
 *
 * @property int $id
 * @property string $belongsTo
 * @property string $name
 * @property string $type
 * @property string|null $defaultValue
 * @property string|null $optionValue
 * @property string|null $gridValue
 * @property bool|null $isTable
 * @property bool|null $isInvoice
 * @property bool|null $isRequired
 * @property bool|null $isAdmin
 * @property bool|null $isDisable
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 */
class CustomField extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'belongs_to',
        'name',
        'type',
        'default_value',
        'option_value',
        'grid_value',
        'is_table',
        'is_invoice',
        'is_required',
        'is_admin',
        'is_disable',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_table' => 'boolean',
            'is_invoice' => 'boolean',
            'is_required' => 'boolean',
            'is_admin' => 'boolean',
            'is_disable' => 'boolean',
        ];
    }
}

