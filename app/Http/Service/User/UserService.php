<?php

namespace App\Http\Service\User;

use App\Http\Service\Base\BaseService;
use Illuminate\Database\Eloquent\Builder;

class UserService extends BaseService
{
    protected function initialize(): void
    {
        $this->setSearchableFields(['name', 'email']);

        $this->setSorters(['name', 'email', 'created_at']);

        $this->setTabFilters([
            'active' => fn (Builder $query) => $query->whereNull('deleted_at'),
            'archived' => fn (Builder $query) => $query->onlyTrashed(),
        ]);
    }
}
