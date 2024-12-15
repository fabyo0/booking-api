<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionEnum: string
{
   case PROPERTIES_MANAGE = 'properties-manage';
   case BOOKINGS_MANAGE = 'bookings-manage';
   case MANAGE_USERS = 'manage-users';

    public function label(): string
    {
        return $this->value;
    }
}
