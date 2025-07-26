<?php

use App\Http\Controllers\Api\PagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('verifyApiKey')->group(function () {

    Route::get('/{slug}/cms', [PagesController::class, 'getCms'])->name('cms');
});
