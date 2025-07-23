<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse as TwoFactorChallengeViewResponseContract;

class TwoFactorChallengeResponse implements TwoFactorChallengeViewResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : Inertia::render('auth/TwoFactorChallenge')->toResponse($request);
    }
}
