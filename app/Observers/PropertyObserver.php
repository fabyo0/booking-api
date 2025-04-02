<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;

final class PropertyObserver
{
    public function creating(Property $property): void
    {
        if (app()->environment('testing')) {
            $property->lat = '40.7128';
            $property->long = '-74.0060';
            return;
        }

        // Check owner
        if (Auth::check()) {
            $property->owner_id = Auth::id();
        }

        if (null === $property->lat && null === $property->long) {
            $fullAddress = $property->address_street . ','
                . $property->address_postcode . ','
                . $property->city->name . ','
                . $property->city->country->name;

            $result = app('geocoder')->geocode($fullAddress)->get();

            if ($result->isNotEmpty()) {
                $coordinates = $result[0]->getCoordinates();
                $property->lat = $coordinates->getLatitude();
                $property->long = $coordinates->getLongitude();
            }
        }
    }
}
