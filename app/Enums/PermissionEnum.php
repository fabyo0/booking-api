<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_APARTMENT = 'view apartment';
    case EDIT_APARTMENT = 'edit apartment';
    case CREATE_APARTMENT = 'create apartment';
    case DELETE_APARTMENT = 'delete apartment';

    public function label(): string
    {
        return $this->value;
    }
}
