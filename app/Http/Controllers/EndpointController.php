<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Providers\AuthService;
use App\Providers\BookServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

readonly class EndpointController
{
    private BookServiceProvider $bookProvider;
    private Builder $table;

    public function __construct()
    {
        $this->bookProvider = app(BookServiceProvider::class);
        $this->table = DB::table('users');
    }

    public function getAll(): \Illuminate\Support\Collection|array
    {
        return $this->bookProvider->readAll() ?? [];
    }

    public function get($id)
    {
        return $this->bookProvider->read(id: $id) ?? [];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function create(Request $request): array
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

    /**
     * @param Request $request
     * @param string $id
     *
     * @return array
     */
    public function update(Request $request, string $id): array
    {
        $data = $request->get('data');
        $success = $this->bookProvider->update(id: $id, data: $data);
        return [
            "code" => 200,
            "message" => "Book was updated",
            "success" => $success,
        ];
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function delete(string $id): array
    {
        $success = $this->bookProvider->delete(id: $id);
        return [
            "code" => 200,
            "message" => "Book was deleted",
            "success" => $success,
        ];
    }

    public function register(string $email, string $password): array
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

        /** @type AuthService $auth */
        $auth = app(AuthService::class);
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
            ->cookie(Constants::AUTH_NAME, $token, Constants::AUTH_TOKEN_TTL / 60);
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
