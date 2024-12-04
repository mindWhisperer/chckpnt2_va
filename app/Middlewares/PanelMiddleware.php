<?php
namespace App\Middlewares;

use App\Helpers\Constants;
use App\Providers\AuthServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class PanelMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $token = $request->cookies->get(Constants::AUTH_NAME);
        $service = app(AuthServiceProvider::class);

        if (!$token || !$service->validateToken($token, $data) || $data?->ttl < time()) {
            return redirect()->route('login')->cookie(Constants::AUTH_NAME, '', -1);
        }
        $response = $next($request);
        return $response;
    }
}
