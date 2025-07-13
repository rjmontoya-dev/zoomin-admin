<?php

use App\Http\Controllers\Settings\TwoFactorAuthController;
use Illuminate\Support\Facades\Route;

    Route::middleware('auth')->group(function () {
        Route::get('/user/two-factor-authentication', [TwoFactorAuthController::class, 'show'])
            ->name('two-factor.show');

        Route::post('/user/two-factor-authentication', [TwoFactorAuthController::class, 'store'])
            ->name('two-factor.enable');

        Route::post('/user/confirmed-two-factor-authentication', [TwoFactorAuthController::class, 'confirm'])->name('two-factor.confirm');

        Route::delete('/user/two-factor-authentication', [TwoFactorAuthController::class, 'destroy'])
            ->name('two-factor.disable');

        Route::get('/user/two-factor-qr-code', [TwoFactorAuthController::class, 'showQrCode'])->name('two-factor.qr-code');
        Route::get('/user/two-factor-secret-key', [TwoFactorAuthController::class, 'showSecretKey'])->name('two-factor.secret-key');
        Route::get('/user/two-factor-recovery-codes', [TwoFactorAuthController::class, 'showRecoveryCodes'])->name('two-factor.recovery-codes');
        Route::post('/user/two-factor-recovery-codes', [TwoFactorAuthController::class, 'regenerateRecoveryCodes'])->name('two-factor.regenerate-recovery-codes');
        Route::get('/settings/two-factor-authentication', [TwoFactorAuthController::class, 'create'])->name('two-factor.settings');
    });
