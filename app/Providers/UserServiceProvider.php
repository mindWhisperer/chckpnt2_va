<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserServiceProvider
{
    private $table;
    private CommentServiceProvider $commentProvider;
    private BookServiceProvider $bookProvider;

    public function __construct()
    {
        $this->table = DB::table('users');
        $this->commentProvider = app(CommentServiceProvider::class);
        $this->bookProvider = app(BookServiceProvider::class);
    }

    public function getAll(): array
    {
        return $this->table->get()->toArray();
    }

    public function getById(int $id): ?object
    {
        return $this->table->where('id', $id)->first();
    }

    public function create(array $data): bool
    {
        $data['password'] = Hash::make($data['password']);

        if (!isset($data['profile_pic'])) {
            $data['profile_pic'] = 'https://ih1.redbubble.net/image.4623684504.3323/st,small,507x507-pad,600x600,f8f8f8.u7.jpg';
        }
        $data['created_at'] = now();
        $data['email_verified_at'] = now();
        $data['role'] = 'user';


        return $this->table->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if (!isset($data['profile_pic'])) {
            $data['profile_pic'] = 'https://ih1.redbubble.net/image.4623684504.3323/st,small,507x507-pad,600x600,f8f8f8.u7.jpg';
        }
        $data['updated_at'] = now();
        return $this->table->where('id', $id)->update($data);
    }

    public function delete($id): int
    {
        return $this->table->delete(['id' => $id]);
    }

    public function getByEmail(string $email): ?object
    {
        return $this->table->where('email', $email)->first();
    }

    public function getByName(string $username): ?object
    {
        return $this->table->where('name', $username)->first();
    }

    public function deleteUserAndRelatedData(int $userId): bool
    {
        DB::beginTransaction();

        try {
            // 1. Odstrániť všetky komentáre používateľa
            $this->commentProvider->deleteCommentsByUser($userId);

            // 2. Odstrániť používateľa z tabulky kníh (napríklad nastavením na null alebo vymazaním)
            $this->bookProvider->removeUserFromBooks($userId);

            // 3. Odstrániť samotného používateľa
            $success = $this->table->delete(['id' => $userId]);

            if (!$success) {
                throw new \Exception("User deletion failed.");
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting user and related data: " . $e->getMessage());
            return false;
        }
    }
}
