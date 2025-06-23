<?php

namespace App\Http\Controllers\CmsPage;

use App\Models\Admin\Pages\CmsPage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Service\CmsPage\PagesService;
use Illuminate\Http\RedirectResponse;

class PagesController extends Controller
{
    /**
     * @param  PagesService $service
     */
    public function __construct(protected PagesService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(): Response
    {
        return Inertia::render('admin/cms/Index', [
            'items' => CmsPage::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CmsPage $page
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function edit(CmsPage $page, Request $request): Response
    {
        $schema = $this->service->firstSchema($page);

        return Inertia::render('admin/cms/Edit', [
            'page' => $page,
            'schema' => $schema,
            'selectedTab' => $request->input('tab'),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param CmsPage $page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CmsPage $page): RedirectResponse
    {
        $this->service->update($page, $request);

        return redirect()
            ->route('admin.cms.index')
            ->with('success', $page->label . ' successfully updated!');
    }
}
