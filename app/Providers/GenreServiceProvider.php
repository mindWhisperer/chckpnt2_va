<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;

class GenreServiceProvider
{
    private $table;
    public function __construct()
    {
        $this->table = DB::table('genre');
    }

    public function create(array $data): bool
    {
        return $this->table->insert($data);
    }

    public function read($id)
    {
        return $this->table->where('id', $id)->first();
    }

    public function readAll()
    {
        return $this->table->get();
    }

    public function update($id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }
        return $this;
    }

    public function delete($id): int
    {
        return $this->table->where('id', $id)->delete();
    }
}
