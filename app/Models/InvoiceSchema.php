<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * InvoiceSchema Model
 *
 * Represents an invoice numbering schema.
 *
 * @property int $id
 * @property string|null $prefix
 * @property int|null $numberOfDigit
 * @property int|null $startNumber
 * @property string|null $lastInvoiceNumber
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 */
class InvoiceSchema extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoice_schemas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefix',
        'number_of_digit',
        'start_number',
        'last_invoice_number',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number_of_digit' => 'integer',
            'start_number' => 'integer',
        ];
    }
}

