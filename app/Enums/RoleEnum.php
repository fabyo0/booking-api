<?php

namespace App\Enums;

enum RoleEnum: int
{
    case ROLE_ADMINISTRATOR = 1;
    case ROLE_OWNER = 2;
    case ROLE_USER = 3;


    public function label(): string
    {
        return match ($this) {
            self::ROLE_ADMINISTRATOR => 'Administrator',
            self::ROLE_OWNER => 'Owner',
            self::ROLE_USER => 'User',
        };
    }
}
