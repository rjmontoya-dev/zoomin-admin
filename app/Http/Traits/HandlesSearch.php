<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait HandlesSearch
{
    /**
     * Apply search filters to the query using the searchable fields.
     *
     * @param Builder $query
     * @param Request $request
     * @param array $searchable
     * @return void
     */
    public function applySearch(Builder $query, Request $request, array $searchable): void
    {
        $search = $request->get('search');
        if (!$search) return;

        $query->where(function ($q) use ($search, $searchable) {
            foreach ($searchable as $column) {
                if (str_contains($column, '->')) {
                    [$relation, $field] = explode('->', $column);
                    $q->orWhereHas($relation, fn ($q2) => $q2->where($field, 'like', "%{$search}%"));
                } else {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            }
        });
    }
}
