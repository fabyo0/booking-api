<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ValidForRange
{
    public function scopeValidForRange($query, array $range = [])
    {
        return $query->where(fn(Builder $query) => $query
            //first column first item array
            ->where('start_date', '>=', reset($range))
            // end column end item array
            ->where('end_date', '<=', end($range))
            ->orWhere(function (Builder $query) use ($range): void {
                $query->whereBetween('start_date', $range)
                    ->orWhereBetween('end_date', $range);
            })
            ->orWhere(function (Builder $query) use ($range): void {
                $query->where('start_date', '<=', reset($range))
                    ->where('start_date', '>=', end($range));
            }));
    }
}
