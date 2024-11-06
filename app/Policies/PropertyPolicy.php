<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PropertyPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Property $property): Response
    {
        return $user->id == $property->owner_id ?
            Response::allow()
            : Response::deny('You do not own this property.');
    }

    public function reorder(User $user, Property $property, Media $photo): Response
    {
        return $user->id === $property->owner_id && $photo->model_id === $property->id
            ? Response::allow()
            : Response::deny('You are not authorized to reorder photos for this property.');
    }
}
