<?php

namespace App\Middlewares;



use App\Providers\AuthService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class PanelMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $token = $request->cookies->get(\App\Helpers\Constants::AUTH_NAME);
        /** @var AuthService $service */
        $service = app(AuthService::class);

        $user = $service->getUserFromToken($token);

        if (!$user) {
            return redirect()->route('login')->cookie(\App\Helpers\Constants::AUTH_NAME, '', -1);
        }

        // Nastavíme používateľa ako autentifikovaného
        Auth::loginUsingId($user->id);

        return $next($request);
    }
}
