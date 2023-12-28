<?php

namespace App\Enums;

enum RoleEnum: int
{
    case ROLE_ADMINISTRATOR = 1;
    case ROLE_OWNER = 2;
    case ROLE_USER = 3;
}
