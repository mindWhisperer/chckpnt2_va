<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;

class BookServiceProvider
{
    private $table;

    public function __construct()
    {
        $this->table = DB::table('books');
    }

    public function create(array $data): bool
    {
        if (!isset($data['image'])) {
            $data['image'] = 'https://c8.alamy.com/comp/PHY5PH/happy-halloween-clean-white-background-with-face-of-ghost-vector-design-PHY5PH.jpg';
        }

        return $this->table->insert($data);
    }

    public function read($id)
    {
        return $this->table->where('id', $id)->first();
    }

    public function readAll(): \Illuminate\Support\Collection
    {
        return $this->table->get();
    }

    public function update($id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }

        if (!isset($data['image'])) {
            $data['image'] = 'https://c8.alamy.com/comp/PHY5PH/happy-halloween-clean-white-background-with-face-of-ghost-vector-design-PHY5PH.jpg';
        }

        return $this->table->where('id', $id)->update($data);
    }

    public function delete($id): int
    {
        DB::table('comments')->where('book_id', $id)->delete();
        return $this->table->delete(['id' => $id]);
    }

    public function removeUserFromBooks(int $userId)
    {
        return $this->table->where('user_id', $userId)->update(['user_id' => null]);
    }


    public function lastThree(): \Illuminate\Support\Collection
    {
        return $this->table->orderBy('created_at', 'desc')->take(3)->get();
    }

    public function getNewest()
    {
        return $this->table->orderBy('created_at', 'desc')->first();
    }

    public function getCreator($id)
    {
        return DB::table('books')
            ->join('users', 'books.creator_id', '=', 'users.id')
            ->where('books.id', $id) // Opravené: použil sa 'books.id'
            ->select('users.name as creator_name') // Výber mena creator-a
            ->first();
    }

    public function getBooksByCreator($id) {
        return $this->table->where('creator_id', $id)->get();
    }


}
