<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Base\BaseController;
use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Resources\Admin\User\IndexCollection;
use App\Http\Resources\Admin\User\EditResource;
use App\Http\Service\User\UserService;
use App\Models\User;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\Admin\Role\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class UserController extends BaseController
{
    protected function setModel(): string
    {
        return User::class;
    }

    protected function setService(): string
    {
        return UserService::class;
    }

    protected function setIndexResource(): string
    {
        return IndexCollection::class;
    }

    protected function setEditResource(): ?string
    {
        return EditResource::class;
    }

    protected function getViewPath(): string
    {
        return 'admin/users';
    }


    /**
     * Override route prefix if needed.
     */
    protected function getRoutePrefix(): string
    {
        return 'admin.users';
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return parent::baseIndex($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return parent::baseCreate($request, ['roles' => Role::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        return parent::baseStore($request);
    }

    /**
     * Display the specified resource.
     */
    public function edit(Request $request, User $user): Response
    {
        return parent::baseEdit($request, $user, ['roles' => Role::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        return $this->baseUpdate($request, $user->id);
    }
    public function archive(User $user)
    {
        return parent::baseArchive($user->id);
    }

    public function restore($id)
    {
        return parent::baseRestore($id);
    }
}
