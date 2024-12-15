<?php

namespace App\Enums;

enum RoleEnum: int
{
    case ADMINISTRATOR = 1;
    case OWNER = 2;
    case USER = 3;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'name');
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMINISTRATOR => 'Admin',
            self::USER => 'User',
            self::OWNER => 'Owner',
        };
    }
}
