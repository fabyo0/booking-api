<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user,Property $property): Response
    {
        return $user->id == $property->owner_id ?
            Response::allow()
            : Response::deny('You do not own this property.');
    }
}
