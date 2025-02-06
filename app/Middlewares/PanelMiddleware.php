<?php

namespace App\Middlewares;



use App\Providers\AuthService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class PanelMiddleware
{

    public function handle(Request $request, \Closure $next)
    {
        //ziskanie autentidikacneho tokenu z cookie
        $token = $request->cookies->get(\App\Helpers\Constants::AUTH_NAME);
        /** @var AuthService $service */
        $service = app(AuthService::class);

        //ziskame pouzivatela z tokenu
        $user = $service->getUserFromToken($token);

        //ak neexituje tak ideme na login a vymazeme cookie
        if (!$user) {
            return redirect()->route('login')->cookie(\App\Helpers\Constants::AUTH_NAME, '', -1);
        }

        // Nastavíme používateľa ako autentifikovaného
        Auth::loginUsingId($user->id);

        return $next($request);
    }
}
