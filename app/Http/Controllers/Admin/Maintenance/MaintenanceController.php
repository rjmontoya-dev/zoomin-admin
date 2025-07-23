<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Base\BaseController;
use App\Http\Requests\Admin\Maintenance\MaintenanceUpdateRequest;
use App\Http\Resources\Admin\Maintenance\EditResource;
use App\Http\Service\Maintenance\MaintenanceService;
use App\Models\Admin\Maintenance\Maintenance;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class MaintenanceController extends BaseController
{
    protected function setModel(): string
    {
        return Maintenance::class;
    }

    protected function setService(): string
    {
        return MaintenanceService::class;
    }

    protected function setIndexResource(): ?string
    {
        return null;
    }

    protected function setEditResource(): ?string
    {
        return EditResource::class;
    }

    protected function getViewPath(): string
    {
        return 'admin/maintenance';
    }

    protected function getRoutePrefix(): string
    {
        return 'admin.maintenance';
    }

    public function edit(): Response
    {
        $maintenance = Maintenance::firstOrFail();

        return parent::baseEdit(request(), $maintenance);
    }

    public function update(MaintenanceUpdateRequest $request): RedirectResponse
    {
        $maintenance = Maintenance::firstOrFail();

        $this->service->update($request, $maintenance->id);

        return redirect()->route('admin.maintenance.edit')->with('success', 'Maintenance settings updated successfully.');
    }
}
