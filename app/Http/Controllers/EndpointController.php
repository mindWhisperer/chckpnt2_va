<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Providers\AuthService;
use App\Providers\BookServiceProvider;
use App\Providers\CommentServiceProvider;
use App\Providers\UserServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

readonly class EndpointController
{
    private BookServiceProvider $bookProvider;

    private CommentServiceProvider $commentProvider;

    private UserServiceProvider $userProvider;
    private Builder $table;

    public function __construct()
    {
        $this->bookProvider = app(BookServiceProvider::class);
        $this->commentProvider = app(CommentServiceProvider::class);
        $this->userProvider = app(UserServiceProvider::class);
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


    //create profile
    public function register(Request $request)
    {
        $authService = app(AuthService::class);

        $data = $request->get('data');
        $email = $data["email"];
        $name = $data["name"];
        $password = $data["password"];

        // Overenie vstupných dát
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "code" => 401,
                "errors" => [['email', 'Neplatný tvar prihlasovacieho emailu.']],
                "success" => false,
            ];
        }

        if (strlen($password) < 5 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[0-9]/', $password)) {
            return [
                "code" => 401,
                "errors" => [['password', 'Heslo musí mať aspoň 5 znakov, jedno veľké písmeno a jedno číslo.']],
                "success" => false,
            ];
        }


        // Kontrola, či užívateľ existuje
        if ($this->userProvider->getByEmail($email)) {
            sleep(5);
            return [
                "code" => 400,
                "errors" => [['email', 'E-mail je už obsadený.']],
                "success" => false,
            ];
        }

        if($this->userProvider->getByName($name)) {
            sleep(5);
            return [
                "code" => 400,
                "errors" => [['name', 'Meno je už obsadené.']],
                "success" => false,
            ];
        }

        if (!empty($data['profile_pic']) && !filter_var($data['profile_pic'], FILTER_VALIDATE_URL)) {
            sleep(5);
            return [
                "code" => 401,
                "errors" => [['profile_pic', 'URL profilovej fotky nie je platná.']],
                "success" => false,
            ];
        }

        // Hash hesla a vytvorenie užívateľa
        $success = $this->userProvider->create($data);

        if (!$success) {
            return [
                "code" => 500,
                "message" => "Chyba pri registrácii",
                "success" => false,
            ];
        }

        // Získanie nového používateľa
        $user = $this->userProvider->getByEmail($data['email']);

        /** @type AuthService $auth */
        $auth = app(AuthService::class);

        // Vytvorenie JWT tokenu
        $token = $auth->createToken([
            "name" => $user->name,
            "email" => $user->email,
            "role" => 9, // Defaultná rola
        ], Constants::AUTH_TOKEN_TTL);

        return response ([
            "code" => 200,
            "message" => "Registration success",
            "success" => true,
            Constants::AUTH_NAME => $token,
        ], 200)
            ->header('Content-Type', 'application/json')
            ->cookie(Constants::AUTH_NAME, $token,
                Constants::AUTH_TOKEN_TTL / 60);
    }

    //delete profile
    public function deleteProfile(int $userId)
    {
        $success = $this->userProvider->deleteUserAndRelatedData($userId);

        if ($success) {
            return [
                "code" => 200,
                "message" => "User was deleted",
                "success" => $success,
            ];
        }
        return [
            "code" => 500,
            "message" => "Nastala chyba pri odstraňovaní používateľa.",
            "success" => $success,
        ];
    }


    //edit profile
    public function updateProfile(Request $request, string $id): array
    {
        //\Log::info($request->all());
        $data = $request->get('data');
        $success = $this->userProvider->update(id: $id, data: $data);
        return [
            "code" => 200,
            "message" => "Profile was updated",
            "success" => $success,
        ];
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
//        \Log::info("Login Debug:", [
//            "Zadané heslo" => $password,
//            "Uložené heslo v DB" => $user->password,
//            "Overenie Hash::check" => Hash::check($password, $user->password)
//        ]);

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

    /**
     * Získa všetky komentáre pre danú knihu
     */
    public function getComments($bookId)
    {
        return $this->commentProvider->getCommentsForBook($bookId) ?? [];
    }

    /**
     * Pridanie komentára
     */
    public function createComment(Request $request): array
    {
        Log::info('createComment bol zavolaný!');
        $rawData = $request->getContent();
        Log::debug('Raw input:', ['data' => $rawData]);

        // Dekóduj JSON zo stringu
        $decodedData = json_decode($rawData, true);
        //Log::debug('Decoded Data:', ['data' => $decodedData]);

        // Skontroluj, či sú dáta validné
        if (isset($decodedData['data'])) {
            $data = [
                'comment' => $decodedData['data']['comment'],
                'book_id' => $decodedData['data']['book_id'],
                'user_id' => $decodedData['data']['user_id']
            ];
        } else {
            // Ak nie sú údaje v správnom formáte
            $data = [];
        }

        //Log::debug('Request data for adding comment: ', $data);
        //Log::debug('Prijaté dáta:', $request->all());
        // Ak sú chýbajúce údaje
        if (empty($data['comment']) || empty($data['book_id']) || empty($data['user_id'])) {
            $response = [
                "code" => 400,
                "message" => "Chýbajúce údaje pre komentár.",
                "success" => false,
            ];
            //Log::debug('Add comment response: ', $response); // Logovanie odpovede
            return $response;
        }

        // Ak je všetko v poriadku
        $success = $this->commentProvider->createCom($data);

        $response = [
            "code" => 200,
            "message" => "Komentár bol pridaný",
            "success" => $success,
        ];
        Log::debug('Add comment response about to return: ', $response); // Logovanie odpovede
        return $response;
    }


    /**
     * Aktualizácia komentára
     */
    public function updateComment(Request $request, string $id): array
    {
        // Validácia dát pre komentár
        $request->validate([
            'comment' => 'required|string|max:255', // Príklad validácie na minimálnu dĺžku a formát
        ]);

        // Získanie dát z požiadavky
        $data = [
            'comment' => $request->input('comment'),
        ];

        $success = $this->commentProvider->update($id, $data);

        return [
            "code" => 200,
            "message" => "Komentár bol aktualizovaný",
            "success" => $success,
        ];
    }





    /**
     * Odstránenie komentára
     */
    public function deleteComment(string $id): array
    {
        $success = $this->commentProvider->delete(id: $id);

        return [
            "code" => 200,
            "message" => "Comment was deleted",
            "success" => $success,
        ];
    }

}
