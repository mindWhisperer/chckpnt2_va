<?php

namespace App\Middlewares;

use App\Helpers\Constants;
use App\Providers\AuthService;
use Symfony\Component\HttpFoundation\Request;

class PanelMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $token = $request->cookies->get(Constants::AUTH_NAME);
        /** @type AuthService $service */
        $service = app(AuthService::class);

        if (!$service->validateToken($token)) {
            return redirect()->route('login')->cookie(Constants::AUTH_NAME, '', -1);
        }

        $response = $next($request);
        return $response;
    }
}
