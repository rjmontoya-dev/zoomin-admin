<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;

class TwoFactorAuthController extends Controller
{

    public function index(){
        return Inertia::render('settings/partials/TwoFactorAuthForm');
    }

    public function create()
    {
        return Inertia::render('settings/TwoFactorAuth');
    }
    /**
     * Display the user's two-factor authentication status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return response()->json(['enabled' => !is_null($request->user()->two_factor_confirmed_at)]);
    }

    /**
     * Display the user's two-factor authentication QR code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showQrCode(Request $request)
    {
        return $request->user()->twoFactorQrCodeSvg();
    }

    /**
     * Display the user's two-factor authentication secret key.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSecretKey(Request $request)
    {
        return response()->json(['secretKey' => decrypt($request->user()->two_factor_secret)]);
    }

    /**
     * Display the user's two-factor authentication recovery codes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showRecoveryCodes(Request $request)
    {
        return response()->json(['recoveryCodes' => $request->user()->recoveryCodes()]);
    }

    /**
     * Generate new recovery codes for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->user()->replaceRecoveryCodes();

        return response()->json(['status' => 'recovery-codes-regenerated']);
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\EnableTwoFactorAuthentication  $enable
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user());

        $user = $request->user()->fresh();

        return response()->json([
            'svg' => $user->twoFactorQrCodeSvg(),
            'secretKey' => decrypt($user->two_factor_secret),
            'recoveryCodes' => $user->recoveryCodes(),
        ]);
    }

    /**
     * Confirm two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication  $confirm
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        $confirm($request->user(), $request->input('code'));

        return response()->json(['status' => 'two-factor-authentication-confirmed']);
    }

    /**
     * Disable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\DisableTwoFactorAuthentication  $disable
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $disable($request->user());

        return response()->json(['status' => 'two-factor-authentication-disabled']);
    }
}
