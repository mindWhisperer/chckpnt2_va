<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Providers\AuthServiceProvider;
use App\Providers\BookServiceProvider;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

readonly class EndpointController
{
    private BookServiceProvider $bookProvider;
    private \Illuminate\Database\Query\Builder $table;

    public function __construct()
    {
        $this->bookProvider = app(BookServiceProvider::class);
        $this->table=DB::table('users');
    }

    public function getAll(): array
    {
        return $this->bookProvider->readAll() ?? [];
    }

    public function get($id)
    {
        return $this->bookProvider->read(id:$id) ?? [];
    }

    public function create(Request $request):array
    {
        //return $request->get('data');

        $data = $request->get('data');
        $success = $this->bookProvider->create(data: $data);
        $newestRecord = $this->bookProvider->getNewest();
        return [
            "code" => 200,
            "message" => "Book was created",
            "success" => $success,
            "data" => [
                "id" => $newestRecord->id,
            ],
        ];
    }

    public function update(Request $request, string $id):array
    {
        $data = $request->get('data');
        $success = $this->bookProvider->update(id: $id, data: $data);
        return [
            "code" => 200,
            "message" => "Book was updated",
            "success" => $success,
        ];
    }

    public function delete(string $id):array
    {
        $success = $this->bookProvider->delete(id: $id);
        return [
            "code" => 200,
            "message" => "Book was deleted",
            "success" => $success,
        ];
    }

    public function register(string $email, string $password):array
    {
        return [];
    }

    public function login(Request $request)
    {
        $data = $request->get('data');
        $email = $data["email"];
        $password = $data["password"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "code" => 401,
                "errors" => [['email', 'Nesprávny tvar prihlasovacieho emailu.']],
                "success" => false,
            ];
        }
        if (empty($password) || strlen($password) < 3) {
            return [
                "code" => 401,
                "errors" => [['password', 'Heslo musí mať aspoň 3 znaky.']],
                "success" => false,
            ];
        }
        $user = $this->table->where('email', $email)->first();
        if (!$user) {
            return [
                "code" => 401,
                //"message" => "User not exist",
                "errors" => [['login', 'Nesprávne prihlasovacie údaje']],
                "success" => false,
            ];
        }
        $auth = app(AuthServiceProvider::class);
        if (!$auth->validatePassword($password, $user->password)) {
            return [
                "code" => 401,
                "message" => "Invalid password",
                "errors" => [['login', 'Nesprávne prihlasovacie údaje']],
                "success" => false,
            ];
        }
        $token = $auth->createToken([
            "name" => $user->name,
            "email" => $user->email,
            //"role" => $user->role,
            "role" => 9,
        ], Constants::AUTH_TOKEN_TTL);
        return response([
            "code" => 200,
            "message" => "Login success",
            "success" => true,
            Constants::AUTH_NAME => $token,
        ], 200)
            ->header('Content-Type', 'application/json')
            // $name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null
            ->cookie(Constants::AUTH_NAME, $token, Constants::AUTH_TOKEN_TTL);
    }

    public function logout()
    {
        return response([
            "code" => 200,
            "message" => "Logout success",
            "success" => true,
        ], 200)->cookie(Constants::AUTH_NAME, '', -1);
    }

    public function checkPassword(string $password): array
    {
        return [$password];
    }
}

