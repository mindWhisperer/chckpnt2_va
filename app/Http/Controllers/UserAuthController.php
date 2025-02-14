<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserAuthController
{
    function login(Request $request)
    {
        $prev = $request->headers->get('referer');
        if ($request->cookies->has(Constants::AUTH_NAME) && $prev && !str_contains($prev, 'login')) {
            return redirect()->route('login')->with('redirect_to', $request->url());

        }

        return view('layouts.login');
    }

    function logout(Request $request): RedirectResponse
    {
        return redirect()->route('login')->cookie(Constants::AUTH_NAME, '', -1);
    }

    function register(Request $request)
    {
        $prev = $request->headers->get('referer');
        if ($request->cookies->has(Constants::AUTH_NAME) && $prev && !str_contains($prev, 'register')) {
            return redirect()->to($prev);
        }

        return view('layouts.register');
    }

    private function apiData(Request $request): array
    {
        $data = $request->get('data') ?? [];
        if (is_string($data)) {
            try {
                $data = json_decode($data, true);
            } catch (\Exception $e) {
                $data = [];
            }
        }
        return $data;
    }

}
