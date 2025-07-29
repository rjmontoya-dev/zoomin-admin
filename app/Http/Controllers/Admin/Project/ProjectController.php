<?php

namespace App\Http\Controllers\Admin\Project;

use App\Enums\Statuses\PaymentScheduleStatus;
use App\Enums\Statuses\PaymentStatus;
use App\Enums\Types\PaymentType;
use App\Http\Controllers\Base\BaseController;
use App\Http\Requests\Admin\Project\ProjectStoreRequest;
use App\Http\Requests\Admin\Project\ProjectUpdateRequest;
use App\Http\Resources\Admin\Project\EditResource;
use App\Http\Resources\Admin\Project\IndexCollection;
use App\Http\Service\Project\ProjectService;
use App\Models\Admin\Project;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Statuses\ProjectStatus;
use App\Enums\Types\BusinessType;
use App\Models\User;

class ProjectController extends BaseController
{
    protected function getIndexProps(Request $request): array
    {
        return [
            'status' => ProjectStatus::getOptionList(),
        ];
    }

    protected function additionalProps(): array
    {
        return [
            'projectStatuses' => ProjectStatus::getOptionList(),
            'businessTypes' => BusinessType::getOptionList(),
            'paymentTypes' => PaymentType::getOptionList(),
            'paymentStatuses' => PaymentStatus::getOptionList(),
            'paymentScheduleStatuses' => PaymentScheduleStatus::getOptionList(),
            'agents' => User::all()->map(fn($item) => [
                'id' => $item->id,
                'label' => $item->name,
            ]),
        ];
    }

    protected function setModel(): string
    {
        return Project::class;
    }

    protected function setService(): string
    {
        return ProjectService::class;
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
        return 'admin/projects';
    }

    protected function getRoutePrefix(): string
    {
        return 'admin.projects';
    }

    public function index(Request $request): Response
    {
        return $this->baseIndex($request, $this->getIndexProps($request));
    }

    public function create(Request $request): Response
    {
        return $this->baseCreate($request, $this->additionalProps());
    }

    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        return $this->baseStore($request);
    }

    public function edit(Request $request, Project $project): Response
    {
        $project->load('paymentDetail.paymentSchedules', 'repairs');
        return $this->baseEdit($request, $project, $this->additionalProps());
    }

    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        return $this->baseUpdate($request, $project);
    }

    public function destroy(string $id): RedirectResponse
    {
        return $this->baseDestroy($id);
    }
}
