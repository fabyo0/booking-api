<?php

declare(strict_types=1);

namespace App\Http\Controllers\Owner;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class PropertyPhotoController
{
    /**
     * Store Property Photo
     *
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(Property $property, Request $request): array
    {
        $request->validate([
            'photo' => ['image', 'max:5000'],
        ]);

        Gate::authorize('create',$property);

        $photo = $property->addMediaFromRequest('photo')->toMediaCollection('photos');

        // Media position increment
        $position = Media::query()
            ->where('model_type', Property::class)
            ->where('model_id', $property->id)
            ->max('position') + 1;

        $photo->position = $position;
        $photo->save();

        return [
            'filename' => $photo->getUrl(),
            'thumbnail' => $photo->getUrl('thumbnail'),
            'position' => $photo->position,
        ];
    }

    /**
     * Photo Reorder
     *
     * @return int[]
     *
     * @ignoreParam property
     * @ignoreParam photo
     */
    public function reorder(Property $property, Media $photo, int $newPosition): array
    {
        Gate::authorize('reorder', [$property, $photo]);

        // Check property
        $query = Media::query()
            ->where('model_id', $photo->model_id)
            ->where('model_type', Property::class);

        //Increment position
        if ($newPosition < $photo->position) {
            $query->whereBetween('position', [$newPosition, $photo->position - 1])
                ->increment('position');
        } // Decrement position
        else {
            $query->whereBetween('position', [$photo->position + 1, $newPosition])
                ->decrement('position');
        }
        $photo->position = $newPosition;
        $photo->save();

        return [
            'photo' => $photo,
            'newPosition' => $newPosition,
        ];
    }
}
