<?php

namespace App\Traits\Scopes;

/**
 * Created by Sunil Thatal.
 * Date Time: 2022-01-02 15:01:00
 * responsible for active status
 * example: $query->active();
 */
trait ActiveScope
{
    /**
     * Scope a query to only include active
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
