<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait HandlesTabs
{
     /**
     * Get default tab counts for 'all' and 'archived' entries.
     *
     * @param Builder $query
     * @return array
     */
    public function getTabCounts(Builder $query): array
    {
        return [
            'all' => $query->count(),
            'archived' => (clone $query)->whereNotNull('deleted_at')->count(),
        ];
    }

    /**
     * Apply a custom tab filter query.
     *
     * @param callable $callback
     * @param Builder $query
     * @return Builder
     */
    public function setTabFilter(callable $callback, Builder $query): Builder
    {
        return $callback($query);
    }
}
