<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

abstract class BaseController extends Controller
{
    protected $service;

    /**
     * Define the model class to be used by the controller.
     */
    abstract protected function setModel(): string;

    /**
     * Define the service class to be used by the controller.
     */
    abstract protected function setService(): string;

    /**
     * Define the service class to be used by the controller.
     */
    abstract protected function setIndexResource(): string;

    /**
     * Define the resource class for the edit view.
     */
    abstract protected function setEditResource(): ?string;


    /**
     * Define relationships to always load.
     */
    protected function getWith(): array
    {
        return [];
    }

    /**
     * Define relationship counts to load.
     */
    protected function getWithCount(): array
    {
        return [];
    }

    /**
     * Define the base view path (e.g., 'Articles' for Articles/Index, Articles/Create, etc.).
     */
    abstract protected function getViewPath(): string;

    public function __construct()
    {
        $serviceClass = $this->setService();
        $this->service = new $serviceClass(
            $this->setModel(),
            $this->getWith(),
            $this->getWithCount(),
            $this->setIndexResource(),
            $this->setEditResource(),
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function baseIndex(Request $request): Response
    {
        $data = $this->service->index($request);

        return Inertia::render($this->getViewPath() . '/Index', array_merge([
            'data' => $data['data'],
            'tabCounts' => $data['tabCounts'],
            'meta' => $data['meta'],
        ], $this->getIndexProps($request)));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function baseCreate(Request $request, $props=[]): Response
    {
        return Inertia::render($this->getViewPath() . '/Create',  array_merge($props,$this->getCreateProps()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function baseStore(FormRequest $request): RedirectResponse
    {
        try {
            $resource = DB::transaction(fn () => $this->service->store($request));
            return redirect()
                ->route($this->getRoutePrefix() . '.index')
                ->with('success', $this->getStoreSuccessMessage());

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function baseEdit(Request $request, Model $model, $props = []): Response
    {
        $data = $this->service->edit($model);

        return Inertia::render(
            $this->getViewPath() . '/Edit',
            array_merge($props, $this->getEditProps($data['item']), ['item' => $data['item']])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function baseUpdate(Request $request, string $id): RedirectResponse
    {
        try {
            $resource = $this->service->update($request, $id);

            return redirect()
                ->route($this->getRoutePrefix() . '.index')
                ->with('success', $this->getUpdateSuccessMessage());

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function baseDestroy(string $id): RedirectResponse
    {
        $this->service->destroy($id);

        return redirect()
            ->back()
            ->with('success', $this->getDestroySuccessMessage());
    }

    /**
     * Archive the specified resource (soft delete).
     */
    public function baseArchive(string $id): RedirectResponse
    {
        $this->service->archive($id);

        return redirect()
            ->back()
            ->with('success', $this->getArchiveSuccessMessage());
    }

    /**
     * Restore the specified resource from archive.
     */
    public function baseRestore(string $id): RedirectResponse
    {
        $this->service->restore($id);

        return redirect()
            ->back()
            ->with('success', $this->getRestoreSuccessMessage());
    }

    /**
     * Get additional props for index view. Override in child controllers.
     */
    protected function getIndexProps(Request $request): array
    {
        return [];
    }

    /**
     * Get additional props for create view. Override in child controllers.
     */
    protected function getCreateProps(): array
    {
        return [];
    }

    /**
     * Get additional props for edit view. Override in child controllers.
     */
    protected function getEditProps($item): array
    {
        return [];
    }

    /**
     * Get the route prefix for redirects. Override if needed.
     */
    protected function getRoutePrefix(): string
    {
        return strtolower(class_basename(static::class, 'Controller'));
    }

    /**
     * Success messages - override these in child controllers if needed.
     */
    protected function getStoreSuccessMessage(): string
    {
        return ucfirst($this->getResourceName()) . ' created successfully.';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return ucfirst($this->getResourceName()) . ' updated successfully.';
    }

    protected function getDestroySuccessMessage(): string
    {
        return ucfirst($this->getResourceName()) . ' deleted successfully.';
    }

    protected function getArchiveSuccessMessage(): string
    {
        return ucfirst($this->getResourceName()) . ' archived successfully.';
    }

    protected function getRestoreSuccessMessage(): string
    {
        return ucfirst($this->getResourceName()) . ' restored successfully.';
    }

    /**
     * Get the resource name for messages.
     */
    protected function getResourceName(): string
    {
        return strtolower(str_replace('Controller', '', class_basename(static::class)));
    }

    /**
     * Get the service instance for additional operations.
     */
    protected function getService()
    {
        return $this->service;
    }
}
