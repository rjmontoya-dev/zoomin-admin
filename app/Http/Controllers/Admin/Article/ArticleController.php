<?php

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Base\BaseController;
use App\Http\Requests\Admin\Article\ArticleStoreRequest;
use App\Http\Resources\Admin\Article\IndexCollection;
use App\Http\Resources\Admin\Article\EditResource;
use App\Http\Service\Article\ArticleService;
use App\Models\Admin\Article;
use App\Http\Requests\Admin\Article\ArticleUpdateRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends BaseController
{
    protected function setModel(): string
    {
        return Article::class;
    }

    protected function setService(): string
    {
        return ArticleService::class;
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
        return 'admin/articles';
    }


    /**
     * Override route prefix if needed.
     */
    protected function getRoutePrefix(): string
    {
        return 'admin.articles';
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
        return parent::baseCreate($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $request): RedirectResponse
    {
        return parent::baseStore($request);
    }

    /**
     * Display the specified resource.
     */
    public function edit(Request $request, Article $article): Response
    {
        $props = [
            'status' => [
                ['value' => 'draft', 'label' => 'Draft'],
                ['value' => 'publish', 'label' => 'publish'],
            ],
        ];

        return parent::baseEdit($request, $article, $props);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleUpdateRequest $request, Article $article): RedirectResponse
    {
        return $this->baseUpdate($request, $article->id);
    }
    public function archive(Article $article)
    {
        return parent::baseArchive($article->id);
    }

    public function restore($id)
    {
        return parent::baseRestore($id);
    }
}
