<?php

namespace App\Http\Service\Base;

use App\Http\Resources\Admin\Article\IndexCollection;
use App\Models\Admin\Article;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class BaseService
{
    protected Model $model;
    protected ?string $IndexResourceClass;
    protected ?string $EditResourceClass;
    protected array $with;
    protected array $withCount;
    protected array $searchableFields = [];
    protected array $filters = [];
    protected array $sorters = [];
    protected array $tabFilters = [];
    protected int $defaultPerPage = 5;
    protected int $maxPerPage = 100;
    protected string $defaultSort = 'created_at';
    protected string $defaultSortDirection = 'desc';
    protected string $dateRangeField = 'created_at';

    public function __construct(string $modelClass, array $with = [], array $withCount = [], ?string $indexResourceClass, ?string $editResourceClass = null)
    {
        $this->model = new $modelClass;
        $this->with = $with;
        $this->withCount = $withCount;
        $this->IndexResourceClass = $indexResourceClass;
        $this->EditResourceClass = $editResourceClass;
        $this->initialize();
    }

    /**
     * Initialize service configuration. Override in child services.
     */
    protected function initialize(): void
    {
        // Override in child services to set up filters, sorters, etc.
    }

    /**
     * Handle the index request.
     */
    public function index(Request $request): array
    {
        $query = $this->model->newQuery();

        // Apply relationships
        if (!empty($this->with)) {
            $query->with($this->with);
        }

        if (!empty($this->withCount)) {
            $query->withCount($this->withCount);
        }

        // Apply query modifications
        $this->applyTabFilter($query, $request);
        $this->applySearch($query, $request);
        $this->applyFilters($query, $request);
        $this->applySorters($query, $request);

        // Handle pagination
        $perPage = $this->getPerPageLimit($request);
        $paginated = $query->paginate($perPage);

        return [
            'data' => $this->IndexResourceClass
                ?  $this->IndexResourceClass::collection($paginated->items())->resolve()
                : $paginated->items(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'from' => $paginated->firstItem(),
                'to' => $paginated->lastItem(),
                'has_more_pages' => $paginated->hasMorePages(),
            ],
            'tabCounts' => $this->getTabCounts($request),
            'metaData' => $this->getMetaData(),
        ];
    }

    /**
     * Handle the create form data.
     */
    public function create(): array
    {
        return $this->getFormData();
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request): Model
    {
        $data = $this->prepareStoreData($request);
        return $this->model->create($data);
    }

    /**
     * Show a specific resource.
     */
    public function show(string $id): Model
    {
        $query = $this->model->newQuery();

        if (!empty($this->with)) {
            $query->with($this->with);
        }

        if (!empty($this->withCount)) {
            $query->withCount($this->withCount);
        }

        return $query->findOrFail($id);
    }

    /**
     * Handle the edit form data.
     */
    public function edit(Model $model): array
    {
        if ($this->EditResourceClass) {
            return [
                'item' => $this->EditResourceClass::make($model)->resolve(),
                'formData' => $this->getFormData($model)
            ];
        }

        return [
            'item' => $model,
            'formData' => $this->getFormData($model),
        ];
    }

    /**
     * Update a specific resource.
     */
    public function update(Request $request, string $id): Model
    {
        $item = $this->model->findOrFail($id);

        $data = $this->prepareUpdateData($request, $item);

        $item->update($data);

        return $item->fresh();
    }

    /**
     * Delete a resource (soft delete).
     */
    public function destroy(string $id): bool
    {
        $item = $this->model->findOrFail($id);

        return $item->delete();
    }

    /**
     * Archive a resource (alias for destroy).
     */
    public function archive(string $id): bool
    {
        return $this->destroy($id);
    }

    /**
     * Restore a resource from soft delete.
     */
    public function restore(string $id): bool
    {
        $item = $this->model->withTrashed()->findOrFail($id);

        return $item->restore();
    }

    /**
     * Get form data for create/edit forms. Override in child services.
     */
    protected function getFormData(?Model $item = null): array
    {
        return [];
    }

    /**
     * Prepare data for store operation. Override in child services.
     */
    protected function prepareStoreData(Request $request): array
    {
        return $request->validated();
    }

    /**
     * Prepare data for update operation. Override in child services.
     */
    protected function prepareUpdateData(Request $request, Model $item): array
    {
        if ($request instanceof FormRequest) {
            return $request->validated();
        }

        return $request->all();
    }

    /**
     * Apply search filters to the query.
     */
    protected function applySearch(Builder $query, Request $request): void
    {
        $search = trim($request->get('search', ''));
        if (empty($search)) return;

        $query->where(function ($q) use ($search) {
            foreach ($this->searchableFields as $column) {
                if (str_contains($column, '->')) {
                    [$relation, $field] = explode('->', $column, 2);
                    $q->orWhereHas($relation, function ($q2) use ($field, $search) {
                        $q2->where($field, 'like', "%{$search}%");
                    });
                } else {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            }
        });
    }

    /**
     * Apply filters to the query.
     */
    protected function applyFilters(Builder $query, Request $request): void
    {
        $filters = $request->get('filters', []);
        if (empty($filters)) {
            return;
        }

        // Handle standard filters
        foreach ($this->filters as $field => $callback) {
            if (isset($filters[$field]) && $filters[$field] !== '' && !is_null($filters[$field])) {
                if (is_callable($callback)) {
                    $callback($query, $filters[$field]);
                }
            }
        }

        // Handle date range filter
        if (isset($filters['dateRange']) && is_array($filters['dateRange'])) {
            $this->applyDateRangeFilter($query, $filters['dateRange'], $request);
        }
    }

    /**
     * Apply date range filters.
     */
    protected function applyDateRangeFilter(Builder $query, array $dateRange, Request $request): void
    {
        if (empty($dateRange['start']) || empty($dateRange['end'])) {
            return;
        }

        $timezone = $request->get('timezone', config('app.timezone'));

        try {
            $from = \Carbon\Carbon::parse($dateRange['start'], $timezone)->startOfDay();
            $to = \Carbon\Carbon::parse($dateRange['end'], $timezone)->endOfDay();
            $query->whereBetween($this->dateRangeField, [$from, $to]);
        } catch (\Exception $e) {
            Log::warning('Invalid date format in dateRange filter', [
                'dates' => $dateRange,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Apply sorting to the query.
     */
    public function applySorters(Builder $query, Request $request): void
    {
        $field = $request->get('sort', $this->defaultSort);
        $direction = strtolower($request->get('direction', $this->defaultSortDirection));

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = $this->defaultSortDirection;
        }

        if (in_array($field, $this->sorters) || $field === $this->defaultSort) {
            $query->orderBy($field, $direction);
        }
    }


    /**
     * Apply tab filters to the query.
     */
    protected function applyTabFilter(Builder $query, Request $request): void
    {
        $tab = $request->get('tab', 'all');

        switch ($tab) {
            case 'archived':
                if ($this->hasSoftDeletes()) {
                    $query->onlyTrashed();
                }
                break;
            case 'with_archived':
                if ($this->hasSoftDeletes()) {
                    $query->withTrashed();
                }
                break;
            case 'all':
            default:
                if (isset($this->tabFilters[$tab]) && is_callable($this->tabFilters[$tab])) {
                    $this->tabFilters[$tab]($query);
                }
                break;
        }
    }

    /**
     * Get tab counts.
     */
    protected function getTabCounts(Request $request): array
    {
        $counts = ['all' => $this->model->newQuery()->count()];

        if ($this->hasSoftDeletes()) {
            $counts['archived'] = $this->model->newQuery()->onlyTrashed()->count();
            $counts['with_archived'] = $this->model->newQuery()->withTrashed()->count();
        }

        foreach ($this->tabFilters as $tab => $callback) {
            if (is_callable($callback)) {
                $tabQuery = $this->model->newQuery();
                $callback($tabQuery);
                $counts[$tab] = $tabQuery->count();
            }
        }

        return $counts;
    }

    /**
     * Get meta data for the response.
     */
    protected function getMetaData(): array
    {
        return [
            'searchableFields' => $this->searchableFields,
            'availableFilters' => array_keys($this->filters),
            'availableSorters' => $this->sorters,
            'availableTabs' => array_merge(['all', 'archived', 'with_archived'], array_keys($this->tabFilters)),
        ];
    }

    /**
     * Get pagination limit with validation.
     */
    protected function getPerPageLimit(Request $request): int
    {
        $perPage = (int) $request->get('perPage', $this->defaultPerPage);
        return min(max($perPage, 1), $this->maxPerPage);
    }

    /**
     * Check if the model uses soft deletes.
     */
    protected function hasSoftDeletes(): bool
    {
        return method_exists($this->model, 'bootSoftDeletes');
    }

    /**
     * Configuration methods for child services.
     */
    protected function setSearchableFields(array $fields): void
    {
        $this->searchableFields = $fields;
    }

    protected function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    protected function setSorters(array $sorters): void
    {
        $this->sorters = $sorters;
    }

    protected function setTabFilters(array $tabFilters): void
    {
        $this->tabFilters = $tabFilters;
    }

    protected function addFilter(string $field, callable $callback): void
    {
        $this->filters[$field] = $callback;
    }

    protected function addSorter(string $field): void
    {
        if (!in_array($field, $this->sorters)) {
            $this->sorters[] = $field;
        }
    }

    protected function addTabFilter(string $tab, callable $callback): void
    {
        $this->tabFilters[$tab] = $callback;
    }
}
