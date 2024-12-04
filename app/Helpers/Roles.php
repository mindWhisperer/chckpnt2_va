<?php

namespace App\Helpers;

use App\Providers\AuthServiceProvider;
use Illuminate\Http\Request;

class Roles
{
    public static function role(array $roleNames): bool
    {
        return in_array('admin',$roleNames,true);
    }

    public static function isLogged(): bool
    {
        $request = app(Request::class);
        $authService=app(AuthServiceProvider::class);
        $token = $request->cookies->get(Constants::AUTH_NAME);
        return $authService->validateToken($token);
    }
}
