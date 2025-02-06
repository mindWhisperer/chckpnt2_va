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
        $data['created_at'] = now();
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
        $data['updated_at'] = now();
        return $this->table->where('id', $id)->update($data);
    }

    public function delete($id): bool
    {
        \Log::info("Trying to delete comment with ID: " . $id);

        $deletedRows = $this->table->where('id', $id)->delete();

        \Log::info("Rows deleted: " . $deletedRows);

        return $deletedRows > 0;
    }


    public function getCommentsForBook($bookId)
    {
        return $this->table
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.book_id', $bookId)
            ->select('comments.*', 'users.name as user_name') // Načítanie mena používateľa
            ->get();
    }

    public function deleteCommentsByUser(int $userId)
    {
        return $this->table->where('user_id', $userId)->delete();
    }

}
