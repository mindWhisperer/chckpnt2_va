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

        $token = $request->cookies->get(Constants::AUTH_NAME);

        $service = app(AuthServiceProvider::class);

        return $token && $service->validateToken($token,$data) && $data?->ttl >= time();
    }
}
