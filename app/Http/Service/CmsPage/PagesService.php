<?php

namespace App\Http\Service\CmsPage;

use App\Http\Traits\FileHandler;
use App\Models\Admin\Pages\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class PagesService
{
    use FileHandler;
    /**
     * Updates the CMS page
     *
     * @param   CmsPage $cmsPage
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function update(CmsPage $cmsPage, Request $request)
    {
        $schema = $this->firstSchema($cmsPage);

        $this->validateRules($schema, $request);
        $this->populateData($schema, $request, $cmsPage);

        $cmsPage->save();

        $this->updateCache($cmsPage);
    }

    /**
     * Update the cache based on the given CmsPage Model
     *
     * @param CmsPage $cmsPage
     *
     * @return void
     */
    public function updateCache(CmsPage $cmsPage)
    {
        Cache::put($cmsPage->slug, $cmsPage->content ?? []);
    }

    /**
     * Get the schema base on the given CmsPage model
     *
     * @param CmsPage $cmsPage
     *
     * @return array
     */
    public function firstSchema(CmsPage $cmsPage): array
    {
        $schema = $this->getSchema($cmsPage);

        return reset($schema); // Get first element
    }

    /**
     * Get Schema from config
     *
     * @param CmsPage $cmsPage
     *
     * @return array
     */
    protected function getSchema(CmsPage $cmsPage): array
    {
        return Arr::where(config('cms.schema'), function ($page) use ($cmsPage) {
            return $page['slug'] === $cmsPage->slug;
        });
    }

    /**
     * Populate page
     *
     * @param array $schema
     * @param \Illuminate\Http\Request $request
     * @param CmsPage $cmsPage
     *
     * @return void
     */
    protected function populateData(array $schema, Request $request, CmsPage &$cmsPage): void
    {
        $cmsPage->content = $this->buildContent($schema, $request, $cmsPage->content);
        $cmsPage->title = $request->title;
        $cmsPage->description = $request->description;
        $cmsPage->keywords = $request->keywords;

        if ($request->hasFile('og_image_path')) {
            $file = $request->file('og_image_path');
            $path = $file->store('cms_images');
            $cmsPage->og_image_path = $path;
        }

        if($request->input('og_image_path')){
            $cmsPage->og_image_path = $this->storeBase64File($request->og_image_path, 'seo_images');
        }

        $cmsPage->robots_follow = $request->robots_follow;
        $cmsPage->robots_index = $request->robots_index;
    }

    /**
     * Build content
     *
     * @param array $schema
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function buildContent(array $schema, Request $request, array|null $existingContent = []): array
    {
        $content = [];

        foreach ($schema['sections'] as $section) {
            foreach ($section['items'] as $item) {
                $key = "{$section['id']}_{$item['id']}";
                switch ($item['type']) {
                    case 'text':
                    case 'textarea':
                    case 'checkbox':
                    case 'url':
                        $content[$key] = $request->$key;
                        break;
                    case 'htmleditor':
                        $content[$key] = $request->$key;
                        break;
                    case 'image':
                        $file_key = $key . '_file';

                        if ($request->hasFile($file_key)) {
                            $content[$key] = $request->$file_key->store('cms_images');
                        }
                        elseif ($request->has($key) && !empty($request->$key) && strpos($request->$key, 'data:image') === 0) {
                            $content[$key] = $this->storeBase64File($request->$key, 'cms_images');
                        }
                        elseif ($request->$key === '' || $request->$key === null  ) {
                            $content[$key] = null;
                        }
                        elseif (isset($existingContent[$key])) {
                            $content[$key] = $existingContent[$key];
                        }
                        else {
                            $content[$key] = null;
                        }
                        break;
                    case 'video':
                        $file_key = $key . '_file';

                        if ($request->hasFile($file_key)) {
                            $content[$key] = $request->$file_key->store('cms_videos');
                        }
                        elseif ($request->has($key) && !empty($request->$key) && strpos($request->$key, 'data:video') === 0) {
                            $content[$key] = $this->storeBase64File($request->$key, 'cms_videos');
                        }
                        elseif ($request->$key === '' || $request->$key === null  ) {
                            $content[$key] = null;
                        }
                        elseif (isset($existingContent[$key])) {
                            $content[$key] = $existingContent[$key];
                        }
                        else {
                            $content[$key] = null;
                        }
                        break;
                    case 'file':
                        $file_key = $key . '_file';

                        if ($request->hasFile($file_key)) {
                            $content[$key] = $request->$file_key->store('cms_documents');
                        }
                        elseif ($request->has($key) && !empty($request->$key) && strpos($request->$key, 'data:') === 0) {
                            $content[$key] = $this->storeBase64File($request->$key, 'cms_documents');
                        }
                        elseif (isset($existingContent[$key])) {
                            $content[$key] = $existingContent[$key];
                        }
                        else {
                            $content[$key] = null;
                        }
                        break;
                    case 'list_table':
                        // Initialize the array
                        $content[$key] = [];

                        if ($request->has($key) && is_string($request->$key)) {
                            $jsonInput = $request->input($key);
                            $decoded = json_decode($jsonInput, true);

                            // Check if we have valid JSON
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $length = count($decoded);

                                for ($i = 0; $i < $length; $i++) {
                                    $item_array = [];
                                    foreach ($item['items'] as $list_item) {
                                        $item_id = $list_item['id'];
                                        switch ($list_item['type']) {
                                            case 'text':
                                            case 'textarea':
                                            case 'checkbox':
                                            case 'url':
                                            case 'htmleditor':
                                                if (isset($decoded[$i][$item_id])) {
                                                    $item_array[$item_id] = $decoded[$i][$item_id];
                                                }
                                                break;
                                            case 'video':
                                                if (isset($decoded[$i][$item_id]) && !empty($decoded[$i][$item_id])
                                                    && strpos($decoded[$i][$item_id], 'data:video') === 0) {
                                                    $item_array[$item_id] = $this->storeBase64File($decoded[$i][$item_id], 'cms_videos');
                                                }
                                                elseif (isset($decoded[$i][$item_id]) && !empty($decoded[$i][$item_id])) {
                                                    $item_array[$item_id] = $decoded[$i][$item_id];
                                                }
                                                break;
                                            case 'image':
                                                if (isset($decoded[$i][$item_id]) && !empty($decoded[$i][$item_id])
                                                    && strpos($decoded[$i][$item_id], 'data:image') === 0) {
                                                    $item_array[$item_id] = $this->storeBase64File($decoded[$i][$item_id], 'cms_images');
                                                }
                                                elseif (isset($decoded[$i][$item_id]) && !empty($decoded[$i][$item_id])) {
                                                    $item_array[$item_id] = $decoded[$i][$item_id];
                                                }
                                                break;
                                            case 'file':
                                                if (isset($decoded[$i][$item_id]) && !empty($decoded[$i][$item_id])
                                                    && strpos($decoded[$i][$item_id], 'data:') === 0) {
                                                    $item_array[$item_id] = $this->storeBase64File($decoded[$i][$item_id], 'cms_documents');
                                                }
                                                elseif (isset($decoded[$i][$item_id]) && !empty($decoded[$i][$item_id])) {
                                                    $item_array[$item_id] = $decoded[$i][$item_id];
                                                }
                                                break;
                                        }
                                    }

                                    if (!empty($item_array)) {
                                        $content[$key][] = $item_array;
                                    }
                                }
                            }
                        }
                        break;
                }
            }
        }

        return $content;
    }

    /**
     * Rules for validating content
     *
     * @param array $schema
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function validateRules(array $schema, Request $request): void
    {
        $rules = [];

        foreach ($schema['sections'] as $section) {
            foreach ($section['items'] as $item) {
                if (array_key_exists('rules', $item)) {
                    switch ($item['type']) {
                        case 'text':
                        case 'textarea':
                        case 'checkbox':
                        case 'url':
                            $rules["{$section['id']}_{$item['id']}"] = $item['rules'];
                            break;
                        case 'image':
                            # Apply file rules if string component is null
                            if ($request->{"{$section['id']}_{$item['id']}"} === null) {
                                $rules["{$section['id']}_{$item['id']}_file"] = $item['rules'];
                            }
                            break;
                        case 'video':
                            # Apply file rules if string component is null
                            if ($request->{"{$section['id']}_{$item['id']}"} === null) {
                                $rules["{$section['id']}_{$item['id']}_file"] = $item['rules'];
                            }
                            break;
                        case 'file':
                            # Apply file rules if string component is null
                            if ($request->{"{$section['id']}_{$item['id']}"} === null) {
                                $rules["{$section['id']}_{$item['id']}_file"] = $item['rules'];
                            }
                            break;

                    }
                }
            }
        }

        $request->validate($rules);
    }

}
