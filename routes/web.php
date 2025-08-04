<?php

use App\Http\Controllers\Admin\ActivityLog\ActivityLogController;
use App\Http\Controllers\Admin\Article\ArticleController;
use App\Http\Controllers\Admin\Maintenance\MaintenanceController;
use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\Repair\RepairController;
use App\Http\Controllers\Admin\RolePermission\RolePermissionController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\CmsPage\PagesController;
use App\Http\Controllers\Settings\TwoFactorAuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        Route::prefix('cms')
            ->name('cms.')
            ->controller(PagesController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{page}/edit', 'edit')->name('edit');
                Route::post('/{page}', 'update')->name('update');
            });

        Route::prefix('articles')
            ->name('articles.')
            ->controller(ArticleController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/{article}/edit', 'edit')->name('edit');
                Route::post('/{article}/update', 'update')->name('update');
                Route::delete('/{article}/archive', 'archive')->name('archive');
                Route::post('/{article}/restore', 'restore')->name('restore');
            });

        Route::prefix('maintenance')
            ->name('maintenance.')
            ->controller(MaintenanceController::class)
            ->group(function () {
                Route::get('/', 'edit')->name('edit');
                Route::post('/update', 'update')->name('update');
            });

        Route::prefix('users')
            ->name('users.')
            ->controller(UserController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::post('/{user}/update', 'update')->name('update');
                Route::delete('/{user}/archive', 'archive')->name('archive');
                Route::post('/{user}/restore', 'restore')->name('restore');
            });

        Route::prefix('roles-permissions')
            ->name('roles-permissions.')
            ->controller(RolePermissionController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{role}/edit', 'edit')->name('edit');
            });

        Route::prefix('activity-log')
            ->name('activity-log.')
            ->controller(ActivityLogController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });
        Route::prefix('projects')
            ->name('projects.')
            ->controller(ProjectController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/{project}', 'show')->name('show');
                Route::get('/{project}/edit', 'edit')->name('edit');
                Route::post('/{project}/update', 'update')->name('update');
                Route::patch('/{project}/update-status', 'updateStatus')->name('update-status');
                Route::delete('/{project}/archive', 'archive')->name('archive');
                Route::post('/{project}/restore', 'restore')->name('restore');
            });
        Route::prefix('projects/{project}/repairs')
            ->name('projects.repairs.')
            ->controller(RepairController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/{repair}/edit', 'edit')->name('edit');
                Route::post('/{repair}/update', 'update')->name('update');
                Route::delete('/{repair}/archive', 'archive')->name('archive');
                Route::post('/{repair}/restore', 'restore')->name('restore');
            });

        Route::prefix('schedules')
            ->name('schedules.')
            ->controller(ScheduleController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'schedules')->name('data');
            });
    });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/two-factor.php';
