<?php

use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\WebsiteVisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('verifyApiKey')->group(function () {

    Route::get('/{slug}/cms', [PagesController::class, 'getCms'])->name('cms');
    Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');
    Route::post('/visits/record', [WebsiteVisitController::class, 'recordVisit'])->name('visits.record');
});
