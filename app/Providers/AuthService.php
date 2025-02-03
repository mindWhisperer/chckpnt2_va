<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthService
{

    private readonly string $key;
    private const algo = 'HS256';

    public function __construct()
    {
        /** @noinspection SpellCheckingInspection */
        $this->key = env('JWT_SECRET', env('APP_KEY', 'U8grDuf4DPnRlWK6xIr7qRi7pzeya0Tj-GFOZft7EBI'));
    }

    function createToken(array $payload, int $ttl = 3600): string
    {
        return JWT::encode(
            payload: array_merge($payload, ["ttl" => time() + $ttl]),
            key: $this->key,
            alg: self::algo,
        );
    }

    function getTokenData(string $token): array|null
    {
        $this->validateToken(token: $token, data: $decoded);
        return json_decode(json_encode($decoded), true) ?? [];
    }

    function validateToken(string|null $token, &$data = null): bool
    {
        if (empty($token)) {
            return false;
        }

        try {
            $token = $this->parseTokenBearer($token);
            $data = JWT::decode($token, new Key(
                keyMaterial: $this->key,
                algorithm: self::algo,
            ));

            return !($data->ttl < time());

        } catch (\Exception $e) {
            return false;
        }
    }

    function createPassword(string $password): string
    {
        return Hash::make(value: $password);
    }

    function validatePassword(string $password, string $hashedPassword): bool
    {
        return Hash::check(value: $password, hashedValue: $hashedPassword);
    }

    private function parseTokenBearer(string $rawToken): string
    {
        $needle = 'Bearer ';
        if (str_contains($rawToken, $needle)) {
            return substr($rawToken, strlen($needle));
        }
        return $rawToken;
    }

    public function getUserFromToken(?string $token)
    {
        if (!$token) {
            return null;
        }

        $data = null;
        if (!$this->validateToken($token, $data) || !isset($data->email)) {
            return null;
        }

        return DB::table('users')->where('email', $data->email)->first();
    }


}
