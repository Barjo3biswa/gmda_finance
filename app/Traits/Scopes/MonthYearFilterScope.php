<?php
namespace App\Traits\Scopes;

/**
 * Created by Sunil Thatal.
 * Date Time: 2020-01-02 15:01:00
 * 
 */
trait MonthYearFilterScope
{
    /**
     * Scope a query to only include active
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMonthYearFilter($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }
}
