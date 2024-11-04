<?php

namespace App\Observers;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PropertyObserver
{
    public function creating(Property $property): void
    {
        // Check owner
        if (Auth::check()) {
            $property->owner_id = Auth::id();
        }

        if (is_null($property->lat) && is_null($property->long)) {
            $fullAddress = $property->address_street.','
                .$property->address_postcode.','
                .$property->city->name.','
                .$property->city->country->name;

            $result = app('geocoder')->geocode($fullAddress)->get();

            if ($result->isNotEmpty()) {
                $coordinates = $result[0]->getCoordinates();
                $property->lat = $coordinates->getLatitude();
                $property->long = $coordinates->getLongitude();
            }
        }
    }
}
