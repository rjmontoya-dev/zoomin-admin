<?php

namespace App\Http\Controllers\Admin\Repair;

use App\Enums\Statuses\RepairStatus;
use App\Http\Controllers\Base\BaseController;
use App\Http\Requests\Admin\Repair\RepairStoreRequest;
use App\Http\Requests\Admin\Repair\RepairUpdateRequest;
use App\Http\Resources\Admin\Repair\EditResource;
use App\Http\Resources\Admin\Repair\IndexCollection;
use App\Http\Service\Repair\RepairService;
use App\Models\Admin\Project;
use App\Models\Admin\Repair;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RepairController extends BaseController
{
    protected function setModel(): string
    {
        return Repair::class;
    }

    protected function setService(): string
    {
        return RepairService::class;
    }

    protected function setIndexResource(): ?string
    {
        return IndexCollection::class;
    }

    protected function setEditResource(): ?string
    {
        return EditResource::class;
    }

    protected function getViewPath(): string
    {
        return 'admin/repairs';
    }

    protected function getRoutePrefix(): string
    {
        // This will be used for redirects, but since the routes are nested,
        // we will handle redirects manually in each method.
        return 'admin.projects.repairs';
    }

    public function index(Request $request, Project $project): Response
    {
        $request->merge(['project_id' => $project->id]);
        $data = $this->service->index($request);

        return Inertia::render($this->getViewPath() . '/Index', [
            'project' => $project,
            'data' => $data['data'],
            'tabCounts' => $data['tabCounts'],
            'meta' => $data['meta'],
        ]);
    }

    public function create(Request $request, Project $project): Response
    {
        
        return Inertia::render($this->getViewPath() . '/Create', [
            'project' => $project,
            'status' => RepairStatus::getOptionList(),
        ]);
    }

    public function store(RepairStoreRequest $request, Project $project): RedirectResponse
    { 
        try { 

            DB::transaction(fn () => $this->service->repairStore($request, $project));

            return redirect()
                ->route('admin.projects.repairs.index', $project)
                ->with('success', $this->getStoreSuccessMessage());

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    public function edit(Request $request, Project $project, Repair $repair): Response
    {
        $data = $this->service->edit($repair);

        return Inertia::render($this->getViewPath() . '/Edit', [
            'project' => $project,
            'item' => $data['item'],
            'status' => RepairStatus::getOptionList(),
        ]);
    }

    public function update(RepairUpdateRequest $request, Project $project, Repair $repair): RedirectResponse
    {
        try {
            $this->service->update($request, $repair);

            return redirect()
                ->route('admin.projects.repairs.index', $project)
                ->with('success', $this->getUpdateSuccessMessage());

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    public function archive(Project $project, Repair $repair): RedirectResponse
    {
        $this->service->archive($repair->id);

        return redirect()
            ->back()
            ->with('success', $this->getArchiveSuccessMessage());
    }

    public function restore(Project $project, string $id): RedirectResponse
    {
        $this->service->restore($id);

        return redirect()
            ->back()
            ->with('success', $this->getRestoreSuccessMessage());
    }
}