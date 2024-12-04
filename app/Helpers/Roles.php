<?php

namespace App\Helpers;

use App\Providers\AuthService;
use Illuminate\Http\Request;

class Roles
{
    public static function role(array $roleNames): bool
    {
        return in_array('admin', $roleNames, true);
    }

    public static function isLogged(): bool
    {
        /** @type Request $request */
        $request = app(Request::class);

        /** @type AuthService $authService */
        $authService = app(AuthService::class);

        $token = $request->cookies->get(Constants::AUTH_NAME);

        return $authService->validateToken($token);
    }
}
