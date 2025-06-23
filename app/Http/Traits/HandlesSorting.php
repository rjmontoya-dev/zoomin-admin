<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait HandlesSorting
{
     /**
     * Apply sorting to the query based on allowed fields.
     *
     * @param Builder $query
     * @param Request $request
     * @param array $sorters
     * @return void
     */
    public function applySorters(Builder $query, Request $request, array $sorters): void
    {
        $sort = $request->get('sort', []);

        foreach ($sort as $field => $direction) {
            if (in_array($field, $sorters)) {
                $query->orderBy($field, $direction);
            }
        }
    }
}
