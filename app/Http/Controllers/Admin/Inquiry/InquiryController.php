<?php

namespace App\Http\Controllers\Admin\Inquiry;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Admin\Inquiry\EditResource;
use App\Http\Resources\Admin\Inquiry\IndexCollection;
use App\Http\Service\Inquiry\InquiryService;
use App\Models\Admin\Inquiry\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InquiryController extends BaseController
{
    protected function setModel(): string
    {
        return Inquiry::class;
    }

    protected function setService(): string
    {
        return InquiryService::class;
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
        return 'admin/inquiry';
    }

    /**
     * @return Response
     */
    public function index(Request $request): Response
    {
        return parent::baseIndex($request);
    }

    /**
     * @param Inquiry $inquiry
     * @return Response
     */
    public function show(Request $request, Inquiry $inquiry): Response
    {
        return Inertia::render('admin/inquiry/Show', [ 'item' => $inquiry ]);
    }

    /**
     * @param string $id
     * @return RedirectResponse
     */
    public function archive(string $id): RedirectResponse
    {
        return parent::baseArchive($id);
    }

    /**
     * @param string $id
     * @return RedirectResponse
     */
    public function restore(string $id): RedirectResponse
    {
        return parent::baseRestore($id);
    }
}
