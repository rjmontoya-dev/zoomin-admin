<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HandlesFilters
{
      /**
     * Apply filters to the query based on defined filter callbacks and default rules.
     *
     * @param Builder $query
     * @param Request $request
     * @param array $filters
     * @return void
     */
    public function applyFilters(Builder $query, Request $request, array $filters): void
    {
        $filterValues = $request->get('filters', []);

        foreach ($filters as $field => $callback) {
            if (isset($filterValues[$field])) {
                $callback($query, $filterValues[$field]);
            }
        }

        // Default created_at date range
        if (isset($filterValues['created_at']) && is_array($filterValues['created_at'])) {
            $timezone = $request->get('timezone', config('app.timezone'));
            $from = Carbon::parse($filterValues['created_at'][0], $timezone)->startOfDay();
            $to = Carbon::parse($filterValues['created_at'][1], $timezone)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }
    }
}
