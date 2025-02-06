<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Providers\AuthService;
use App\Providers\BookServiceProvider;
use App\Providers\CommentServiceProvider;
use App\Providers\UserServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
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

    //create knihy
    public function create(Request $request): array
    {
        $data = $request->get('data');

        //validacia
        if (empty($data['name'])) {
            return [
                "code" => 400,
                "errors" => [['name', 'Názov knihy je povinný.']],
                "success" => false,
            ];
        }
        if (empty($data['description'])) {
            return [
                "code" => 400,
                "errors" => [['description', 'Popis knihy je povinný.']],
                "success" => false,
            ];
        }
        if (isset($data['image']) && !filter_var($data['image'], FILTER_VALIDATE_URL)) {
            return [
                "code" => 400,
                "errors" => [['image', 'Nesprávny formát URL obrázku.']],
                "success" => false,
            ];
        }
        if (empty($data['genre'])) {
            return [
                "code" => 400,
                "errors" => [['genre', 'Žáner je povinný.']],
                "success" => false,
            ];
        }

        //vytvorenie knihy
        $success = $this->bookProvider->create(data: $data);
        $newestRecord = $this->bookProvider->getNewest();

        //vratenie odpovede po vytvoreni
        return [
            "code" => 200,
            "message" => "Book was created",
            "success" => $success,
            "data" => [
                "id" => $newestRecord->id,
            ],
        ];
    }

    //update knihy
    public function update(Request $request, string $id): array
    {
        $data = $request->get('data');
        //validacia
        if (empty($data['name'])) {
            return [
                "code" => 400,
                "errors" => [['name', 'Názov knihy je povinný.']],
                "success" => false,
            ];
        }
        if (empty($data['description'])) {
            return [
                "code" => 400,
                "errors" => [['description', 'Popis knihy je povinný.']],
                "success" => false,
            ];
        }
        if (isset($data['image']) && !filter_var($data['image'], FILTER_VALIDATE_URL)) {
            sleep(5);
            return [
                "code" => 400,
                "errors" => [['image', 'Nesprávny formát URL obrázku.']],
                "success" => false,
            ];
        }
        if (empty($data['genre'])) {
            return [
                "code" => 400,
                "errors" => [['genre', 'Žáner je povinný.']],
                "success" => false,
            ];
        }

        //data na aktualizaciu pre knihu
        $updateData = [
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $data['image'] ?? null,
            'genre' => $data['genre'],
            'updated_at' => now(),
        ];
        //pridanie dat do databazy
        $success = $this->bookProvider->update(id: $id, data: $updateData);
        return [
            "code" => 200,
            "message" => "Book was updated",
            "success" => $success,
        ];
    }

    //delete knihy
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
        $data = $request->get('data');
        $email = $data["email"];
        $name = $data["name"];
        $password = $data["password"];

        // Overenie vstupných dát
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sleep(5);
            return [
                "code" => 401,
                "errors" => [['email', 'Neplatný tvar prihlasovacieho emailu.']],
                "success" => false,
            ];
        }

        if (strlen($password) < 5 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[0-9]/', $password)) {
            sleep(5);
            return [
                "code" => 401,
                "errors" => [['password', 'Heslo musí mať aspoň 5 znakov, jedno veľké písmeno a jedno číslo.']],
                "success" => false,
            ];
        }

        // Kontrola, či užívateľ existuje
        $existingUser = $this->table->where('email', '=', $email)->first();
        if ($existingUser) {
            sleep(5);
            return [
                "code" => 400,
                "errors" => [['email', 'E-mail je už obsadený.']],
                "success" => false,
            ];
        }

        $existingName = $this->table->where('name', '=', $name)->first();
        if ($existingName) {
            sleep(5);
            return [
                "code" => 400,
                "errors" => [['name', 'Meno je už obsadené.']],
                "success" => false,
            ];
        }

        //pridanie filtru na url
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
            return response()->json([
                'success' => true,
                'message' => 'Profil bol úspešne odstránený.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nastala chyba pri odstraňovaní profilu.'
        ], 500);
    }

    //edit profile
    public function updateProfile(Request $request, string $id): array
    {
        $data = $request->get('data');
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                "code" => 400,
                "errors" => [['email', 'Nesprávny formát emailu.']],
                "success" => false,
            ];
        }
        if (empty($data['name'])) {
            return [
                "code" => 400,
                "errors" => [['name', 'Meno je povinné.']],
                "success" => false,
            ];
        }

        if (!empty($data['password']) && (strlen($data['password']) < 5 || !preg_match('/[A-Z]/', $data['password']) || !preg_match('/[0-9]/', $data['password']))) {
            return [
                "code" => 400,
                "errors" => [['password', 'Heslo musí mať aspoň 5 znakov, jedno veľké písmeno a jedno číslo.']],
                "success" => false,
            ];
        }

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
            sleep(5);
            return [
                "code" => 401,
                "errors" => [['email', 'Nesprávny tvar prihlasovacieho emailu.']],
                "success" => false,
            ];
        }

        if (strlen($password) < 5 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            sleep(5);
            return [
                "code" => 401,
                "errors" => [['password', 'Heslo musí mať aspoň 5 znakov, jedno veľké písmeno a jedno číslo.']],
                "success" => false,
            ];
        }

        $user = $this->table
            ->where('email', '=', $email) // Query Builder ochrana proti SQL Injection
            ->first();

        if (!$user) {
            sleep(5);
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
            sleep(5);
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
            "role" => $user->role,
            //"role" => 9,
        ], Constants::AUTH_TOKEN_TTL);

        return response([
            "code" => 200,
            "message" => "Login success",
            "success" => true,
            Constants::AUTH_NAME => $token,
        ], 200)
            ->header('Content-Type', 'application/json')
            ->cookie(
                Constants::AUTH_NAME,
                $token,
                Constants::AUTH_TOKEN_TTL / 60,
                '/', // Path
                null, // Domain (optional)
                true, // Secure
                true, // HttpOnly
                false, // Raw
                'None' // SameSite Policy (None, Lax, Strict)
            );
    }

    public function logout()
    {
        return response([
            "code" => 200,
            "message" => "Logout success",
            "success" => true,
        ], 200)->cookie(Constants::AUTH_NAME, '', -1);
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
        // Získanie surových dát
        $rawData = $request->getContent();

        // Dekóduj JSON zo stringu
        $decodedData = json_decode($rawData, true);

        // Skontroluj, či sú dáta validné
        if (!isset($decodedData['data'])) {
            return [
                "code" => 400,
                "message" => "Chýbajúce údaje pre komentár.",
                "success" => false,
            ];
        }

        // Priradenie údajov
        $data = [
            'comment' => $decodedData['data']['comment'] ?? '',
            'book_id' => $decodedData['data']['book_id'] ?? '',
            'user_id' => $decodedData['data']['user_id'] ?? ''
        ];

        // Validácia údajov
        if (empty($data['comment']) || empty($data['book_id']) || empty($data['user_id'])) {
            return [
                "code" => 400,
                "message" => "Chýbajúce údaje pre komentár.",
                "success" => false,
            ];
        }

        // Pridanie komentára do databázy
        $success = $this->commentProvider->createCom($data);

        // Vytvorenie odpovede
        $response = [
            "code" => $success ? 200 : 500,
            "message" => $success ? "Komentár bol pridaný" : "Došlo k chybe pri pridávaní komentára",
            "success" => $success,
        ];

        return $response;
    }



    /**
     * Aktualizácia komentára
     */
    public function updateComment(Request $request, string $id): array
    {
        $data = [
            'comment' => $request['data']['comment'] ?? '',
            'book_id' => $request['data']['book_id'] ?? '',
            'user_id' => $request['data']['user_id'] ?? ''
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
