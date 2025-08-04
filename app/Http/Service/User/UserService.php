<?php

namespace App\Http\Service\User;

use App\Http\Service\Base\BaseService;
use Illuminate\Database\Eloquent\Builder;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    public function store(Request $request): User
    {
        /** @var User $user */
        $user = parent::store($request );

        if ($request->has('role_id')) {
            $user->syncRoles($request->input('role_id'));
        }

        return $user;
    }
    public function update(Request $request, Model $user): User
    {
        $user = parent::update($request, $user);

        if ($request->has('role_id')) {
            $user->syncRoles($request->input('role_id'));
        }

        return $user;
    }
}
