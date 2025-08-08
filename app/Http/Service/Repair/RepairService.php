<?php

namespace App\Http\Service\Repair;

use App\Enums\Statuses\RepairStatus;
use App\Http\Service\Base\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RepairService extends BaseService
{
    /**
     * Initialize service configuration.
     */
    protected function initialize(): void
    {
        $this->setTabFilters([
            'done' => fn (Builder $query) => $query->where('status', RepairStatus::DONE()),
            'on_going' => fn (Builder $query) => $query->where('status', RepairStatus::ONGOING()),
            'pending' => fn (Builder $query) => $query->where('status', RepairStatus::PENDING()),
            'archived' => fn (Builder $query) => $query->onlyTrashed(),
        ]);

        $this->setSearchableFields([
            'reported_issue',
            'remarks',
        ]);

        $this->setSorters([
            'date_reported',
            'status',
            'repair_date',
            'amount',
            'date_finished',
            'created_at',
        ]);

        $this->setFilters([
            'status' => fn(Builder $query, $value) => $query->where('status', $value),
            'project_id' => fn(Builder $query, $value) => $query->where('project_id', $value),
        ]);
    }

    public function index(Request $request): array
    {
        if ($request->has('project_id')) {
            $request->merge([
                'filters' => array_merge($request->input('filters', []), ['project_id' => $request->project_id])
            ]);
        }

        return parent::index($request);
    }
    public function repairStore(Request $request, Model $model)
    {
        $data = $request->validated();
        $model->repairs()->create($data);
        return $model;
    }
}
