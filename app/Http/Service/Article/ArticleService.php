<?php

namespace App\Http\Service\Article;

use App\Http\Service\Base\BaseService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ArticleService extends BaseService
{
    protected function initialize(): void
    {
        $this->setSearchableFields(['title']);

        $this->setFilters([
            'status' => fn (Builder $query, $value) => $query->where('status', $value),
        ]);

        $this->setSorters(['title', 'status', 'created_at']);

        $this->setTabFilters([
            'published' => fn (Builder $query) => $query->where('status', 'published'),
            'draft' => fn (Builder $query) => $query->where('status', 'draft'),
            'archived' => fn (Builder $query) => $query->onlyTrashed(),
        ]);
    }
}
