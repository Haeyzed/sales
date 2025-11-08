<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * CustomerTypeEnum
 *
 * Enumeration for customer types in the system.
 */
enum CustomerTypeEnum: string
{
    case REGULAR = 'regular';
    case WALKIN = 'walkin';

    /**
     * Get all enum values as an array.
     *
     * @return array<int, string>
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}

