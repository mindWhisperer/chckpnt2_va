<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;

class CommentServiceProvider
{
    private $table;

    public function __construct()
    {
        $this->table = DB::table('comments');
    }

    public function createCom(array $data): bool
    {
        return $this->table->insert($data);

    }

    public function read($id)
    {
        return $this->table->where('id', $id)->first();
    }

    public function readAllForBook($bookId)
    {
        return $this->table->where('book_id', $bookId)->get();
    }

    public function update($id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }
        return $this->table->where('id', $id)->update($data);
    }

    public function delete($id): int
    {
        return $this->table->delete(['id' => $id]);
    }

    public function getCommentsForBook($bookId)
    {
        return $this->table
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.book_id', $bookId)
            ->select('comments.*', 'users.name as user_name') // Načítanie mena používateľa
            ->get();
    }

}
