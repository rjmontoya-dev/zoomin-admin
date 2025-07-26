<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pages\CmsPage;
use Illuminate\Http\Request;

class PagesController extends Controller
{


    public function getCms(string $slug)
    {
        return response()->json([
            'data' => CmsPage::where('slug', $slug)->get(),
        ]);
    }
}
