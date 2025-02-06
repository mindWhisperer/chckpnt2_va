<?php

namespace App\Middlewares;

use App\Helpers\Constants;
use App\Providers\AuthService;
use Symfony\Component\HttpFoundation\Request;

class ApiMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        //ziskanie tokena z cookie alebo null ak neni
        $token = $request->cookies->get(Constants::AUTH_NAME) ?? null;
        /** @type AuthService $service */
        $service = app(AuthService::class);

        //overenie ci je token platny
        if (!$service->validateToken($token)) {
            //ak neni tak vratime odpoved a odstranime cookie
            return response(
                [
                    "code" => 401,
                    "message" => "Unauthorized",
                    "success" => false
                ], 401)
                ->cookie(Constants::AUTH_NAME, '', -1);
        }
        //ak platny pokracujeme a odpoved vratime do app
        $response = $next($request);
        return $response;
    }
}
